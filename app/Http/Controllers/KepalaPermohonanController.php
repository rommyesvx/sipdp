<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\PermohonanData;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class KepalaPermohonanController extends Controller
{
    public function index(Request $request)
    {
        // --- Menyiapkan Data untuk Kartu Statistik ---
        $jumlahDiproses = PermohonanData::where('status', 'diajukan')->count();
        $jumlahEskalasi = PermohonanData::where('status', 'eskalasi')->count();

        // --- Menyiapkan Data untuk Tabel ---
        $sortableColumns = ['updated_at', 'created_at'];
        $sort = in_array($request->query('sort'), $sortableColumns) ? $request->query('sort') : 'updated_at';
        $direction = in_array($request->query('direction'), ['asc', 'desc']) ? $request->query('direction') : 'desc';

        // Query utama: HANYA mengambil data dengan status 'eskalasi'
        $permohonans = PermohonanData::with('user')
            ->where('status', 'eskalasi')
            ->orderBy($sort, $direction)
            ->paginate(15); // 15 permohonan per halaman

        // Kirim semua data ke view
        return view('kepala.permohonan.index', [
            'permohonans' => $permohonans,
            'jumlahDiproses' => $jumlahDiproses,
            'jumlahEskalasi' => $jumlahEskalasi,
            'sort' => $sort,
            'direction' => $direction
        ]);
    }

    public function semuaPermohonan(Request $request)
    {
        $query = PermohonanData::where('status', '!=', 'eskalasi')->with('user');

        // Tambahkan filter jika diperlukan (sangat direkomendasikan)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
            });
        }

        $permohonans = $query->latest()->paginate(15);

        // Ambil daftar status unik untuk dropdown filter
        $statuses = PermohonanData::select('status')
            ->where('status', '!=', 'eskalasi')
            ->distinct()
            ->pluck('status');

        return view('kepala.permohonan.semua', compact('permohonans', 'statuses'));
    }
    
    public function show($id)
    {
        $permohonan = PermohonanData::with(['user'])->findOrFail($id);

        // Keamanan: Pastikan hanya permohonan berstatus eskalasi yang bisa diakses
        if ($permohonan->status !== 'eskalasi') {
            return redirect()->route('kepala.permohonan.index')->with('error', 'Permohonan ini tidak sedang dalam tahap eskalasi.');
        }

        $pegawaisPreview = collect();

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
        if (Str::isJson($permohonan->kolom_diminta) && Str::isJson($permohonan->jenis_data)) {

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

        return view('kepala.permohonan.show', compact(
            'permohonan',
            'pegawaisPreview',
            'kolomTampil',
            'columnLabels'
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

    /**
     * Menyimpan keputusan Kepala Bidang (Setujui/Tolak).
     */
    public function update(Request $request, $id)
    {
        $permohonan = PermohonanData::findOrFail($id);

        // Validasi input
        $request->validate([
            'keputusan' => 'required|in:setujui,tolak',
            // Catatan wajib diisi HANYA JIKA keputusan adalah 'tolak'
            'catatan_kepala_bidang' => 'required_if:keputusan,tolak|nullable|string|max:2000',
        ]);

        // Update status berdasarkan keputusan
        if ($request->keputusan == 'setujui') {
            $permohonan->status = 'sudah eskalasi';
        } else { // jika 'tolak'
            $permohonan->status = 'ditolak kepala bidang';
            $permohonan->alasan_penolakan = $request->catatan_kepala_bidang;
        }

        $permohonan->catatan_kepala_bidang = $request->catatan_kepala_bidang;
        $permohonan->save();

        // Log Aktivitas
        $logMessage = $request->keputusan == 'setujui' ? 'Persetujuan eskalasi diberikan' : 'Permohonan ditolak pada tahap eskalasi';
        activity()->on($permohonan)->causedBy(auth()->user())->log($logMessage);

        return redirect()->route('kepala.permohonan.index')->with('success', 'Keputusan berhasil disimpan.');
    }

    public function downloadHasilPengantar($id)
    {
        $permohonan = PermohonanData::findOrFail($id);

        if (auth()->id() !== $permohonan->user_id) {
            abort(403, 'Unauthorized');
        }
        $filePengantar = $permohonan->suratPengantar;

        if (!$filePengantar || !Storage::disk('public')->exists($filePengantar->path)) {
            return back()->with('error', 'File surat pengantar tidak ditemukan.');
        }

        return Storage::disk('public')->response($filePengantar->path, $filePengantar->nama_asli_file);
    }
}
