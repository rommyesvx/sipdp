<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PermohonanData;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Log;
use App\Exports\PermohonanDataExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


class AdminPermohonanController extends Controller
{

    public function index(Request $request)
    {
        $sortableColumns = ['created_at', 'status'];
        $sort = in_array($request->query('sort'), $sortableColumns) ? $request->query('sort') : 'created_at';
        $direction = in_array($request->query('direction'), ['asc', 'desc']) ? $request->query('direction') : 'desc';
        $permohonans = PermohonanData::with('user')->orderBy($sort, $direction)
            ->paginate(10);
        return view('admin.permohonan.index', [
            'permohonans' => $permohonans,
            'sort' => $sort,
            'direction' => $direction
        ]);
    }

    public function show($id)
    {
        // 1. Ambil data permohonan utama (kode Anda sudah benar)
        $permohonan = PermohonanData::with(['user', 'feedback'])->findOrFail($id);

        // 2. Siapkan variabel-variabel yang akan dikirim ke view
        $pegawaisPreview = collect();
        $kolomTampil = [];
        $columnLabels = [];

        // Kamus untuk menerjemahkan nama kolom DB menjadi label yang ramah
        $columnLabels = [
            'nama' => 'Nama Lengkap',
            'nipBaru' => 'NIP',
            'nik' => 'NIK',
            'agama' => 'Agama',
            'jenisKelamin' => 'Jenis Kelamin',
            'alamat' => 'Alamat',
            'statusPegawai' => 'Status Pegawai',
            'masaKerjaTahun' => 'Masa Kerja (Tahun)',
            'masaKerjaBulan' => 'Masa Kerja (Bulan)',
            'jabatanNama' => 'Nama Jabatan',
            'satuanKerjaKerjaNama' => 'Departemen',
            'golRuangAkhir' => 'Golongan',
            'pendidikanTerakhirNama' => 'Pendidikan Terakhir',
            // Tambahkan mapping lain sesuai dengan semua pilihan checkbox Anda
        ];

        // 3. Cek jika permohonan dibuat dengan kriteria & kolom terstruktur
        if (\Illuminate\Support\Str::isJson($permohonan->kolom_diminta) && \Illuminate\Support\Str::isJson($permohonan->jenis_data)) {

            // --- LOGIKA BARU UNTUK MEMILIH KOLOM ---
            $kolomTampil = json_decode($permohonan->kolom_diminta, true);

            // Filter keamanan untuk memastikan hanya kolom yang diizinkan yang dipilih
            $kolomValid = array_intersect($kolomTampil, array_keys($columnLabels));

            if (!empty($kolomValid)) {
                // --- MEMBANGUN QUERY PEGAWAI BERDASARKAN KRITERIA ---
                $kriteriaList = json_decode($permohonan->jenis_data, true);
                $query = Pegawai::query();

                foreach ($kriteriaList as $item) {
                    $column = $this->mapKriteriaToDbColumn($item['kriteria']);
                    if ($column) {
                        $query->where($column, $item['nilai']);
                    }
                }

                // Terapkan SELECT dinamis HANYA PADA KOLOM YANG VALID
                // dan ambil 10 data pertama sebagai preview
                $pegawaisPreview = $query->select($kolomValid)->take(10)->get();
            }
        }

        // 4. Kirim SEMUA variabel yang dibutuhkan ke view
        return view('admin.permohonan.show', compact(
            'permohonan',
            'pegawaisPreview',
            'kolomTampil',
            'columnLabels'
        ));
    }

    /**
     * Fungsi bantuan untuk mapping kriteria (tetap diperlukan)
     */
    private function mapKriteriaToDbColumn($kriteriaName)
    {
        $map = [
            'Pendidikan' => 'pendidikanTerakhirNama',
            'Agama'      => 'agama',
            'Golongan'   => 'golRuangAkhir',
            'Departemen' => 'satuanKerjaKerjaNama',
            'Posisi'     => 'jabatanNama',
        ];

        return $map[$kriteriaName] ?? null;
    }

    public function export(Request $request, $id)
    {
        $permohonan = PermohonanData::findOrFail($id);

        $kolomTampil = [];
        $columnLabels = [
            'nama' => 'Nama Lengkap',
            'nipBaru' => 'NIP',
            'nik' => 'NIK',
            'agama' => 'Agama',
            'jenisKelamin' => 'Jenis Kelamin',
            'alamat' => 'Alamat',
            'statusPegawai' => 'Status Pegawai',
            'masaKerjaTahun' => 'Masa Kerja (Tahun)',
            'masaKerjaBulan' => 'Masa Kerja (Bulan)',
            'jabatanNama' => 'Nama Jabatan',
            'satuanKerjaKerjaNama' => 'Departemen',
            'golRuangAkhir' => 'Golongan',
            'pendidikanTerakhirNama' => 'Pendidikan Terakhir',
        ];

        $query = Pegawai::query();

        if (\Illuminate\Support\Str::isJson($permohonan->kolom_diminta) && \Illuminate\Support\Str::isJson($permohonan->jenis_data)) {
            $kolomTampilPengguna = json_decode($permohonan->kolom_diminta, true);
            $kolomTampil = array_intersect($kolomTampilPengguna, array_keys($columnLabels));

            if (!empty($kolomTampil)) {
                $kriteriaList = json_decode($permohonan->jenis_data, true);
                foreach ($kriteriaList as $item) {
                    $column = $this->mapKriteriaToDbColumn($item['kriteria']);
                    if ($column) {
                        $query->where($column, $item['nilai']);
                    }
                }
            }
        }

        // Perbedaan utama: Gunakan ->get() untuk mengambil SEMUA data, bukan ->take(10)
        $pegawais = $query->select($kolomTampil)->get();
        // ----------------------------------------------------


        // 2. PROSES EXPORT BERDASARKAN TIPE
        $tipe = $request->query('tipe');
        $fileName = 'hasil-' . $permohonan->id . '-' . now()->format('d-m-Y');

        if ($tipe == 'excel') {
            return Excel::download(new PermohonanDataExport($pegawais, $kolomTampil, $columnLabels), $fileName . '.xlsx');
        }

        if ($tipe == 'pdf') {
            $pdf = Pdf::loadView('pdf.hasil_permohonan', [
                'pegawais' => $pegawais,
                'permohonan' => $permohonan,
                'kolomTampil' => $kolomTampil,
                'columnLabels' => $columnLabels
            ]);
            return $pdf->setPaper('a4', 'landscape')->download($fileName . '.pdf');
        }

        return redirect()->back()->with('error', 'Tipe export tidak valid.');
    }

    public function update(Request $request, $id)
    {
        $permohonan = PermohonanData::findOrFail($id);

        if (in_array($permohonan->status, ['selesai', 'ditolak'])) {
            return back()->with('error', 'Permohonan yang sudah final tidak dapat diubah lagi.');
        }

        $permohonan->status = $request->status;
        $request->validate([
            'status' => 'required|in:selesai,ditolak,eskalasi,diproses',
            'file_hasil' => 'required_if:status,selesai|nullable|file|mimes:pdf,xls,xlsx,csv,zip|max:10240', // Max 10MB
            'alasan_penolakan' => 'required_if:status,ditolak|nullable|string|min:10',
            'alasan_eskalasi' => 'required_if:status,eskalasi|nullable|string|min:10',
        ]);
        if ($request->status === 'selesai') {
            // Jika statusnya 'selesai', proses upload file hasil
            if ($request->hasFile('file_hasil')) {
                $file = $request->file('file_hasil')->store('hasil_permohonan');
                $permohonan->file_hasil = $file;
            }
            $permohonan->alasan_penolakan = null;
            $permohonan->alasan_eskalasi = null;
        } elseif ($request->status === 'ditolak') {
            $permohonan->alasan_penolakan = $request->alasan_penolakan;
            $permohonan->alasan_eskalasi = null;
        } elseif ($request->status === 'eskalasi') {

            $permohonan->alasan_eskalasi = $request->alasan_eskalasi;
            $permohonan->alasan_penolakan = null;
        } else {
            $permohonan->alasan_penolakan = null;
            $permohonan->alasan_eskalasi = null;
        }
        $permohonan->save();

        activity('permohonan_data')
            ->performedOn($permohonan)
            ->causedBy(auth()->user())
            ->withProperties([
                'nomor' => $permohonan->user->no_hp,
                'pesan' => 'Permohonan data Anda telah selesai. Silakan login untuk mengunduh hasilnya.'
            ])
            ->log('Notifikasi WhatsApp berhasil dikirim.');

        if ($permohonan->status === 'selesai') {
            // $this->sendWaNotification($permohonan);

            return back()->with('error', 'Permohonan sudah selesai dan tidak dapat diubah.');
        }

        return redirect()->back()->with('success', 'Permohonan diperbarui');
    }


    public function downloadHasilPengantar($id)
    {
        $permohonan = PermohonanData::findOrFail($id);

        // Cek apakah kolom file ada isinya dan filenya benar-benar ada di storage
        if (!$permohonan->file_permohonan || !Storage::disk('public')->exists($permohonan->file_permohonan)) {
            // Jika tidak ada, kembalikan ke halaman sebelumnya dengan pesan error
            return back()->with('error', 'File surat pengantar tidak ditemukan.');
        }

        // Jika ada, paksa browser untuk mengunduh file dari disk 'public'
        return Storage::disk('public')->response($permohonan->file_permohonan);
    }

    public function downloadHasil($id)
    {
        $permohonan = PermohonanData::findOrFail($id);

        if (auth()->id() !== $permohonan->user_id) {
            abort(403, 'Unauthorized');
        }

        if (!$permohonan->file_hasil || !Storage::exists($permohonan->file_hasil)) {
            abort(404, 'File tidak ditemukan');
        }
        activity()
            ->causedBy(auth()->user())
            ->performedOn($permohonan)
            ->withProperties([
                'file' => $permohonan->file_hasil,
            ])
            ->log('User mendownload file hasil permohonan.');

        return Storage::download($permohonan->file_hasil);
    }

    protected function sendWaNotification($permohonan)
    {
        $nomor = $permohonan->user->no_hp;
        $name = $permohonan->user->name;
        $message = "Permohonan data Anda telah selesai. Silakan login untuk mengunduh hasilnya.";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://bkpsdm.blitarkota.go.id/siap-api/index.php/wa_seleksi_jpt',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'nomor' => $nomor,
                'nip' => $name,
                'message' => $message
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . env('WA_API_AUTH'),
                'Cookie: ci_session=' . env('WA_API_COOKIE')
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::error("WA Notification Error: " . $err);
        } else {
            Log::info("WA Notification Sent: " . $response);
            Log::info("Sending WA", [
                'nomor' => $nomor,
                'message' => $message,
                'response' => $response,
            ]);
        }
    }
}
