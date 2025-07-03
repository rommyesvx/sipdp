<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;

class DataPegawaiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data unik untuk mengisi dropdown filter
        $statuses = Pegawai::select('statusPegawai')->distinct()->whereNotNull('statusPegawai')->pluck('statusPegawai');
        $departemens = Pegawai::select('satuanKerjaKerjaNama')->distinct()->whereNotNull('satuanKerjaKerjaNama')->orderBy('satuanKerjaKerjaNama')->pluck('satuanKerjaKerjaNama');
        $posisis = Pegawai::select('jabatanNama')->distinct()->whereNotNull('jabatanNama')->orderBy('jabatanNama')->pluck('jabatanNama');

        // 2. Mulai query builder
        $query = Pegawai::query();

        // 3. Terapkan filter berdasarkan input dari form
        if ($request->filled('status')) {
            $query->where('statusPegawai', $request->status);
        }
        if ($request->filled('departemen')) {
            $query->where('satuanKerjaKerjaNama', $request->departemen);
        }
        if ($request->filled('posisi')) {
            $query->where('jabatanNama', $request->posisi);
        }
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('nipBaru', 'LIKE', "%{$searchTerm}%");
            });
        }

        // 4. Lakukan pagination pada hasil query
        $pegawais = $query->latest()->paginate(15); // Menampilkan 15 data per halaman

        // 5. Kirim semua data ke view
        return view('admin.dataPegawai.index', compact(
            'pegawais', 
            'statuses', 
            'departemens', 
            'posisis'
        ));
    }
}
