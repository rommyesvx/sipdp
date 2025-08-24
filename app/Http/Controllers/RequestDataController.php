<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanData;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;

class RequestDataController extends Controller
{
    public function showRequestForm()
    {
        $kriteria = [
            'Pendidikan' => Pegawai::select('pendidikanTerakhirNama')->distinct()->whereNotNull('pendidikanTerakhirNama')->pluck('pendidikanTerakhirNama'),
            'Agama'      => Pegawai::select('agama')->distinct()->whereNotNull('agama')->pluck('agama'),
            'Golongan'   => Pegawai::select('golRuangAkhir')->distinct()->whereNotNull('golRuangAkhir')->pluck('golRuangAkhir'),
            'Departemen' => Pegawai::select('satuanKerjaKerjaNama')->distinct()->whereNotNull('satuanKerjaKerjaNama')->pluck('satuanKerjaKerjaNama'),
        ];
        return view('users.requestData', ['kriteria' => $kriteria]);
    }
    public function store(Request $request)
    {
        $request->validate([
        'tujuan' => 'required|string|max:255',
        'tipe' => 'required|string|in:pdf,excel,csv',
        'asal' => 'required|string',
        'jenis_data_kustom' => 'required_if:is_kustom,on|nullable|string|max:2000',
        'jenis_data_kriteria' => 'required_if:is_kustom,null|nullable|json|not_in:[]',
        'kolom' => 'required|array|min:1',
        'file_permohonan' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        'catatan' => 'nullable|string',
    ], [
        'jenis_data_kustom.required_if' => 'Harap jelaskan data yang Anda butuhkan di dalam kotak teks.',
        'jenis_data_kriteria.required_if' => 'Anda harus menambahkan setidaknya satu kriteria data menggunakan tombol "Tambah".',
        'jenis_data_kriteria.not_in' => 'Anda harus menambahkan setidaknya satu kriteria data.',
        'kolom.required' => 'Anda harus memilih setidaknya satu kolom data pada bagian "Pilih Kolom Data".',
        'kolom.min' => 'Anda harus memilih setidaknya satu kolom data pada bagian "Pilih Kolom Data".',
    ]);   
        $path = null;
        if ($request->hasFile('file_permohonan')) {
            $path = $request->file('file_permohonan')->store('permohonan_files', 'public');
        }
         $jenisData = $request->has('is_kustom') 
                    ? $request->input('jenis_data_kustom') 
                    : $request->input('jenis_data_kriteria');

        $permohonan = PermohonanData::create([
            'user_id' => Auth::id(),
            'tujuan' => $request->input('tujuan') === 'Lainnya' ? $request->input('tujuan_lainnya_text', 'Lainnya') : $request->input('tujuan'),
            'tipe' => $request->input('tipe'),
            'asal' => $request->input('asal'),
            'jenis_data' => $jenisData,
            'kolom_diminta' => json_encode($request->input('kolom')),
            'catatan' => $request->input('catatan'),
            // 'file_permohonan' akan di-handle terpisah
        ]);

        if ($request->hasFile('file_permohonan')) {
            $file = $request->file('file_permohonan');
            $path = $file->store('surat_pengantar', 'public'); // Simpan file

            // 3. Buat record baru di tabel file_permohonan yang terhubung dengan permohonan di atas
            $permohonan->files()->create([
                'tipe_file' => 'pengantar',
                'path' => $path,
                'nama_asli_file' => $file->getClientOriginalName(),
            ]);
        }
        return redirect()->route('users.riwayat')->with('success', 'Permohonan berhasil dikirim!');
    }

}
