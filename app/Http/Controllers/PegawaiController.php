<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\PermohonanData;
use App\Models\User;
use App\Models\Feedback;
use Carbon\Carbon;

class PegawaiController extends Controller
{

    public function ambilAllData()
    {
        $pegawai = Pegawai::all();
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
        $jumlahDiajukan = PermohonanData::where('status', 'diajukan')->count();
        $jumlahDieskalasi = PermohonanData::where('status', 'dieskalasi')->count();
        $jumlahDitolak = PermohonanData::where('status', 'ditolak')->count();
        $jumlahSelesai = PermohonanData::where('status', 'selesai')->count();
        $permohonanTerbaru = PermohonanData::with('user')->latest()->take(5)->get();
        $jumlahPengguna = User::count();
        $jumlahPegawai = Pegawai::count();
        $periodeTerbaru = Pegawai::max('periode_data');
        $permohonanBulanan = PermohonanData::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'bulan')
            ->toArray();


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



        $bulanIni = Carbon::now()->month;
        $permohonanBulanIni = PermohonanData::whereMonth('created_at', $bulanIni)->count();
        $permohonanSelesai = PermohonanData::whereMonth('created_at', $bulanIni)->where('status', 'selesai')->count();

        $persentasePelayanan = $permohonanBulanIni > 0
            ? round(($permohonanSelesai / $permohonanBulanIni) * 100, 2)
            : 0;


        return view('admin.index', [
            'jumlah_laki' => $jumlah_laki,
            'jumlah_perempuan' => $jumlah_perempuan,
            'agama_data' => $agamaData,
            'pendidikan_data' => $pendidikanData,
            'periodeTerbaru' => $periodeTerbaru,
            'jumlahDiajukan' => $jumlahDiajukan,
            'jumlahDieskalasi' => $jumlahDieskalasi,
            'jumlahDitolak' => $jumlahDitolak,
            'jumlahSelesai' => $jumlahSelesai,
            'permohonanTerbaru' => $permohonanTerbaru,
            'jumlahPengguna' => $jumlahPengguna,
            'jumlahPegawai' => $jumlahPegawai,
            'permohonanBulanan' => $permohonanBulanan,
            'persentasePelayanan' => $persentasePelayanan,
            'permohonanSelesai' => $permohonanSelesai,
            'permohonanBulanIni' => $permohonanBulanIni
        ]);
    }



    public function feedbackIndex()
    {
        $feedbacks = Feedback::with(['user', 'permohonan'])->latest()->get();
        return view('admin.feedback', compact('feedbacks'));
    }


    public function statistik()
    {
        return view('admin.statistik');
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
