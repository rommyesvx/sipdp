<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;

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
        return view('admin.jeniskelamin', compact('hasil', 'pegawai', 'periodeTerbaru'));
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
