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
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;


class AdminPermohonanController extends Controller
{

    public function index(Request $request)
    {
        $sortableColumns = ['created_at', 'status'];
        $sort = in_array($request->query('sort'), $sortableColumns) ? $request->query('sort') : 'created_at';
        $direction = in_array($request->query('direction'), ['asc', 'desc']) ? $request->query('direction') : 'desc';

        $query = PermohonanData::with('user');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $permohonans = $query->orderBy($sort, $direction)->paginate(15);
        return view('admin.permohonan.index', [
            'permohonans' => $permohonans,
            'sort' => $sort,
            'direction' => $direction
        ]);
    }

    public function show($id)
    {
        $permohonan = PermohonanData::with(['user', 'feedback'])->findOrFail($id);

        $pegawaisPreview = collect();
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

        if (Str::isJson($permohonan->kolom_diminta) && Str::isJson($permohonan->jenis_data)) {

            $kolomDimintaPengguna = json_decode($permohonan->kolom_diminta, true);

            $kolomValid = array_intersect($kolomDimintaPengguna, array_keys($columnLabels));

            if (!empty($kolomValid)) {
                $kolomTampil = $kolomValid;

                $kriteriaList = json_decode($permohonan->jenis_data, true);
                $query = Pegawai::query();

                foreach ($kriteriaList as $item) {
                    $column = $this->mapKriteriaToDbColumn($item['kriteria']);
                    if ($column) {
                        $query->where($column, $item['nilai']);
                    }
                }

                $pegawaisPreview = $query->select($kolomValid)->paginate(15);
            }
        }
        if (!$pegawaisPreview instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $pegawaisPreview = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
        }

        $allLogs = Activity::where('subject_type', PermohonanData::class)
            ->where('subject_id', $permohonan->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // 2. Filter hanya log yang relevan (saat dibuat dan saat diupdate)
        $riwayatStatus = $allLogs->filter(function ($log) {
            return $log->event === 'created' || $log->event === 'updated';
        });

        // 3. Menghitung durasi untuk setiap status
        // Ubah koleksi menjadi array biasa agar mudah di-loop dengan index
        $riwayatArray = $riwayatStatus->values()->all();
        for ($i = 0; $i < count($riwayatArray) - 1; $i++) {
            $logSaatIni = $riwayatArray[$i];
            $logSelanjutnya = $riwayatArray[$i + 1];

            // Tambahkan properti 'durasi' ke setiap objek log
            $logSaatIni->durasi = $logSelanjutnya->created_at->diffForHumans($logSaatIni->created_at, true);
        }

        // 4. Hitung durasi untuk status terakhir hingga saat ini
        if ($riwayatStatus->isNotEmpty() && !in_array($permohonan->status, ['selesai', 'ditolak'])) {
            $logTerakhir = $riwayatStatus->last();
            $logTerakhir->durasi = now()->diffForHumans($logTerakhir->created_at, true);
        }

        return view('admin.permohonan.show', compact(
            'permohonan',
            'pegawaisPreview',
            'kolomTampil',
            'columnLabels',
            'riwayatStatus'
        ));
    }
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

    public function generateAndAttach(Request $request, $id)
{
    try {
        return DB::transaction(function () use ($request, $id) {
            $permohonan = PermohonanData::findOrFail($id);

            $kolomTampil = [];
            $columnLabels = [
                'nama' => 'Nama Lengkap', 'nipBaru' => 'NIP', 'nik' => 'NIK',
                'agama' => 'Agama', 'jenisKelamin' => 'Jenis Kelamin', 'alamat' => 'Alamat',
                'statusPegawai' => 'Status Pegawai', 'masaKerjaTahun' => 'Masa Kerja (Tahun)',
                'masaKerjaBulan' => 'Masa Kerja (Bulan)', 'jabatanNama' => 'Nama Jabatan',
                'satuanKerjaKerjaNama' => 'Departemen', 'golRuangAkhir' => 'Golongan',
                'pendidikanTerakhirNama' => 'Pendidikan Terakhir',
            ];
            $query = Pegawai::query();
            if (Str::isJson($permohonan->kolom_diminta) && Str::isJson($permohonan->jenis_data)) {
                $kolomDimintaPengguna = json_decode($permohonan->kolom_diminta, true);
                $kolomTampil = array_intersect($kolomDimintaPengguna, array_keys($columnLabels));
                if (!empty($kolomTampil)) {
                    $kriteriaList = json_decode($permohonan->jenis_data, true);
                    foreach ($kriteriaList as $item) {
                        $column = $this->mapKriteriaToDbColumn($item['kriteria']);
                        if ($column) {
                            $query->where($column, $item['nilai']);
                        }
                    }
                } else {
                    $query->whereRaw('1 = 0');
                }
            } else {
                $query->whereRaw('1 = 0');
            }
            $dataUntukExport = $query->select($kolomTampil)->get();
            if ($dataUntukExport->isEmpty()) {
                throw new \Exception('Tidak ada data yang cocok untuk diexport.');
            }

            $tipe = strtolower($permohonan->tipe);
            $relativePath = 'hasil-permohonan/data-' . $permohonan->id . '-' . time();

            if ($tipe === 'pdf') {
                $relativePath .= '.pdf';
                $jenisData = Str::isJson($permohonan->jenis_data) ? json_decode($permohonan->jenis_data, true) : $permohonan->jenis_data;
                
                $pdf = Pdf::loadView('pdf.hasil_permohonan', [
                    'pegawais' => $dataUntukExport, 'permohonan' => $permohonan,
                    'kolomTampil' => $kolomTampil, 'columnLabels' => $columnLabels,
                    'jenisData' => $jenisData
                ]);
                $pdfContent = $pdf->setPaper('a4', 'landscape')->output();

                Storage::disk('public')->put($relativePath, $pdfContent);
            } else {
                $relativePath .= '.xlsx';
                Excel::store(new PermohonanDataExport($dataUntukExport, $kolomTampil, $columnLabels), $relativePath, 'public');
            }

            if (!Storage::disk('public')->exists($relativePath)) {
                throw new \Exception('Gagal menyimpan file ke disk. Periksa izin direktori (permissions).');
            }

            $permohonan->files()->create([
                'tipe_file' => 'hasil',
                'path' => $relativePath,
                'nama_asli_file' => basename($relativePath),
            ]);

            return response()->json([
                'success' => true,
                'file_path' => $relativePath,
                'file_name' => basename($relativePath)
            ]);
        });
    } catch (\Exception $e) {
        Log::error($e);

        return response()->json([
            'success' => false,
            'message' => 'Proses Gagal: ' . $e->getMessage()
        ], 500);
    }
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

        if (Str::isJson($permohonan->kolom_diminta) && Str::isJson($permohonan->jenis_data)) {
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
        $pegawais = $query->select($kolomTampil)->get();
        $tipe = $request->query('tipe');
        $fileName = 'hasil-' . $permohonan->id . '-' . now()->format('d-m-Y');

        if ($tipe == 'excel') {
            return Excel::download(new PermohonanDataExport($pegawais, $kolomTampil, $columnLabels), $fileName . '.xlsx');
        }
        if ($tipe == 'pdf') {
            // Siapkan $jenisData agar tidak undefined di view
            $jenisData = null;
            if (Str::isJson($permohonan->jenis_data)) {
                $jenisData = json_decode($permohonan->jenis_data, true);
            } else {
                $jenisData = $permohonan->jenis_data;
            }
            $pdf = Pdf::loadView('pdf.hasil_permohonan', [
                'pegawais' => $pegawais,
                'permohonan' => $permohonan,
                'kolomTampil' => $kolomTampil,
                'columnLabels' => $columnLabels,
                'jenisData' => $jenisData
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
            'alasan_penolakan' => 'required_if:status,ditolak|nullable|string|min:10',
            'alasan_eskalasi' => 'required_if:status,eskalasi|nullable|string|min:10',
        ]);
        if ($request->status === 'selesai' && $request->hasFile('file_hasil')) {
            $file = $request->file('file_hasil');
            $path = $file->store('file_hasil', 'public'); // Simpan file ke storage

            if ($permohonan->fileHasil) {
                Storage::disk('public')->delete($permohonan->fileHasil->path);
                $permohonan->fileHasil->delete();
            }

            // Buat record baru di tabel file_permohonan
            $permohonan->files()->create([
                'tipe_file' => 'hasil',
                'path' => $path,
                'nama_asli_file' => $file->getClientOriginalName(),
            ]);
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

       
        $filePengantar = $permohonan->suratPengantar;

        if (!$filePengantar || !Storage::disk('public')->exists($filePengantar->path)) {
            return back()->with('error', 'File surat pengantar tidak ditemukan.');
        }

        return Storage::disk('public')->response($filePengantar->path, $filePengantar->nama_asli_file);
    }

    public function downloadHasil($id)
{
    $fileRecord = \App\Models\FilePermohonan::where('permohonan_data_id', $id)
                                             ->where('tipe_file', 'hasil')
                                             ->first();

    if (!$fileRecord) {
        abort(404, 'Data file tidak ditemukan di database.');
    }

    $pathToFile = $fileRecord->path;
    if (!Storage::disk('public')->exists($pathToFile)) {
        abort(404, 'File tidak ditemukan di server. Path: ' . $pathToFile);
    }

    $permohonan = $fileRecord->permohonan; // Mengambil relasi permohonan

    return response()->file(Storage::disk('public')->path($pathToFile));
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
