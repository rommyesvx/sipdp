<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;

class PegawaiController extends Controller
{
    public function ambilAllData()
    {
        $pegawai = Pegawai::all(); // Ambil semua data pegawai
        return view('admin.index', compact('pegawai'));
    }
    public function jeniskelamin($periodeTerbaru = null)
    {
        $periodeTerbaru = $periodeTerbaru ?? date('Y-m') . '-01';
        $pegawai = Pegawai::with('unorInduk')->get();
        $hasil = $pegawai->groupBy(fn($p) => $p->unorInduk->NamaUnor ?? 'Tidak Diketahui')
            ->map(function ($items) use ($periodeTerbaru) {
                return [
                    'L' => $items->where('jenisKelamin', 'M')->where('periode_data', $periodeTerbaru)->count(),
                    'P' => $items->where('jenisKelamin', 'F')->where('periode_data', $periodeTerbaru)->count(),
                ];
            });
        $labels = $hasil->keys();
        $jumlah_laki = $hasil->pluck('L');
        $jumlah_perempuan = $hasil->pluck('P'); 

        return view('admin.jeniskelamin', compact('hasil', 'labels', 'jumlah_laki', 'jumlah_perempuan', 'periodeTerbaru'));
    }

    public function index()
    {
        $periodeTerbaru = Pegawai::max('periode_data');

        // === Jenis Kelamin ===
        $jumlah_laki = Pegawai::where('jenisKelamin', 'M')->where('periode_data', $periodeTerbaru)->count();
        $jumlah_perempuan = Pegawai::where('jenisKelamin', 'F')->where('periode_data', $periodeTerbaru)->count();

        //Agama
        $agamaData = Pegawai::where('periode_data', $periodeTerbaru)
            ->select('agama')
            ->get()
            ->groupBy('agama')
            ->map(fn($group) => $group->count());

        // === Tingkat Pendidikan ===
        $pendidikanData = Pegawai::whereNotNull('tkPendidikanTerakhir')
            ->where('periode_data', $periodeTerbaru)
            ->select('tkPendidikanTerakhir')
            ->get()
            ->groupBy('tkPendidikanTerakhir')
            ->map(fn($group) => $group->count());

        return view('admin.index', [
            'jumlah_laki' => $jumlah_laki,
            'jumlah_perempuan' => $jumlah_perempuan,
            'agama_data' => $agamaData,
            'pendidikan_data' => $pendidikanData,
            'periodeTerbaru' => $periodeTerbaru
        ]);
    }


    public function tingkatPendidikan($periodeTerbaru = null)
    {

        $periodeTerbaru = $periodeTerbaru ?? date('Y-m') . '-01';

        $pegawai = Pegawai::with('unorInduk')
            ->whereNotNull('tkPendidikanTerakhir')
            ->where('periode_data', $periodeTerbaru)
            ->get();

        $hasil = $pegawai->groupBy(fn($p) => $p->unorInduk->NamaUnor ?? 'Tidak Diketahui')
            ->map(function ($items) {
                return $items->groupBy('tkPendidikanTerakhir')->map(fn($group) => $group->count());
            });

        return view('admin.tingkatpendidikan', compact('hasil', 'periodeTerbaru'));
    }

    public function agama($periodeTerbaru = null)
    {
        $periodeTerbaru = $periodeTerbaru ?? date('Y-m') . '-01';

        $pegawai = Pegawai::with('unorInduk')
            ->select('unorIndukId', 'agama', 'periode_data')
            ->where('periode_data', $periodeTerbaru)
            ->get();

        $hasil = $pegawai->groupBy(fn($p) => $p->unorInduk->NamaUnor ?? 'Tidak Diketahui')
            ->map(function ($items) {
                return $items->groupBy('agama')->map(fn($group) => $group->count());
            });

        return view('admin.agama', compact('hasil', 'periodeTerbaru'));
    }
}
