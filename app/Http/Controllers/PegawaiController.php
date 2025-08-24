<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\PermohonanData;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

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
        $jumlahDieskalasi = PermohonanData::where('status', 'eskalasi')->count();
        $jumlahDitolak = PermohonanData::where('status', 'ditolak')->count();
        $jumlahSelesai = PermohonanData::where('status', 'selesai')->count();
        $permohonanTerbaru = PermohonanData::with('user')->latest()->take(5)->get();
        $jumlahPengguna = User::count();
        $jumlahPegawai = Pegawai::count();
        // $periodeTerbaru = Pegawai::max('periode_data');
        $permohonanBulanan = PermohonanData::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'bulan')
            ->toArray();
        $totalPermohonan = PermohonanData::count();

        $totalBulanIni = PermohonanData::whereMonth('created_at', now()->month)->count();
        $selesaiBulanIni = PermohonanData::where('status', 'selesai')->whereMonth('created_at', now()->month)->count();
        $persentaseSelesai = $totalBulanIni > 0 ? round(($selesaiBulanIni / $totalBulanIni) * 100) : 0;

        return view('admin.index', [
            // 'jumlah_laki' => $jumlah_laki,
            // 'jumlah_perempuan' => $jumlah_perempuan,
            // 'agama_data' => $agamaData,
            // 'pendidikan_data' => $pendidikanData,
            // 'periodeTerbaru' => $periodeTerbaru,
            'jumlahDiajukan' => $jumlahDiajukan,
            'jumlahDieskalasi' => $jumlahDieskalasi,
            'jumlahDitolak' => $jumlahDitolak,
            'jumlahSelesai' => $jumlahSelesai,
            'permohonanTerbaru' => $permohonanTerbaru,
            'jumlahPengguna' => $jumlahPengguna,
            'jumlahPegawai' => $jumlahPegawai,
            'permohonanBulanan' => $permohonanBulanan,
            'persentaseSelesai' => $persentaseSelesai,
            'totalBulanIni' => $totalBulanIni,
            'selesaiBulanIni' => $selesaiBulanIni,
            'totalPermohonan' => $totalPermohonan
        ]);
    }



    public function feedbackIndex(Request $request)
    {
        $user = auth()->user();

        $sortableColumns = ['created_at', 'rating'];
        $sort = in_array($request->query('sort'), $sortableColumns) ? $request->query('sort') : 'created_at';
        $direction = in_array($request->query('direction'), ['asc', 'desc']) ? $request->query('direction') : 'desc';

        $query = Feedback::with(['user', 'permohonan']);

        $overallAverageRating = Feedback::avg('rating');

        if ($request->filled('rating') && $request->rating != 'semua') {
            $query->where('rating', $request->rating);
        }


        $filteredAverageRating = $query->clone()->avg('rating');
        $query->orderBy($sort, $direction);
        $feedbacks = $query->paginate(10);


        $feedbacks = $query->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        if ($user->role == 'admin') {
            return view('admin.feedback', [
                'feedbacks' => $feedbacks,
                'sort' => $sort,
                'direction' => $direction,
                'overallAverageRating' => $overallAverageRating,
                'filteredAverageRating' => $filteredAverageRating
            ]);
        }

        if ($user->role == 'kepala') {
            return view('kepala.feedback', [
                'feedbacks' => $feedbacks,
                'sort' => $sort,
                'direction' => $direction,
                'overallAverageRating' => $overallAverageRating,
                'filteredAverageRating' => $filteredAverageRating
            ]);
        }

        abort(403, 'Akses Ditolak');
    }


    public function statistik()
    {
        // === 1. TREN & JUMLAH PERMOHONAN PER BULAN (6 BULAN TERAKHIR) ===
        $trenPermohonan = PermohonanData::select(
            DB::raw("COUNT(*) as count"),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_year")
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month_year')
            ->orderBy('month_year', 'asc')
            ->get()
            ->keyBy('month_year');

        $trenLabels = [];
        $trenData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthKey = $month->format('Y-m');
            $trenLabels[] = $month->format('M Y');
            $trenData[] = $trenPermohonan->get($monthKey)->count ?? 0;
        }

        // === 2. DISTRIBUSI STATUS PERMOHONAN ===
        $distribusiStatus = PermohonanData::groupBy('status')
            ->select('status', DB::raw('count(*) as count'))
            ->pluck('count', 'status');

        $statusLabels = $distribusiStatus->keys()->map(fn($status) => ucfirst($status));
        $statusData = $distribusiStatus->values();

        // === 3. RATA-RATA WAKTU PENYELESAIAN (HARI) ===
        $rataWaktuPenyelesaian = PermohonanData::where('status', 'selesai')
            ->select(DB::raw("AVG(DATEDIFF(updated_at, created_at)) as avg_days"))
            ->value('avg_days');
        $rataWaktu = round($rataWaktuPenyelesaian, 1);

        // === 4. PERMOHONAN PER JENIS DATA (TOP 5) ===
        $allJenisData = PermohonanData::pluck('jenis_data');

        $kriteriaCounts = [];

        foreach ($allJenisData as $jsonData) {
            if (Str::isJson($jsonData)) {
                $kriteriaList = json_decode($jsonData, true);
                if (is_array($kriteriaList)) {
                    foreach ($kriteriaList as $item) {
                        if (isset($item['kriteria'])) {
                            $kriteriaName = $item['kriteria'];
                            if (!isset($kriteriaCounts[$kriteriaName])) {
                                $kriteriaCounts[$kriteriaName] = 0;
                            }
                            $kriteriaCounts[$kriteriaName]++;
                        }
                    }
                }
            }
        }

        arsort($kriteriaCounts);

        // 5. Ambil 5 teratas
        $topKriteria = array_slice($kriteriaCounts, 0, 5, true);

        $jenisDataLabels = array_keys($topKriteria);
        $jenisDataCount = array_values($topKriteria);

        // === 5. TOP 5 USER TERAKTIF ===
        $topUsers = User::withCount('permohonanData')
            ->orderBy('permohonan_data_count', 'desc')
            ->take(5)
            ->get();

        $topUserLabels = $topUsers->pluck('name');
        $topUserCount = $topUsers->pluck('permohonan_data_count');

        // === 6. DISTRIBUSI FEEDBACK RATING ===
        $feedbackCounts = Feedback::groupBy('rating')
            ->select('rating', DB::raw('count(*) as count'))
            ->pluck('count', 'rating');

        $feedbackLabels = ['1 ★', '2 ★', '3 ★', '4 ★', '5 ★'];
        $feedbackData = [
            $feedbackCounts->get(1, 0),
            $feedbackCounts->get(2, 0),
            $feedbackCounts->get(3, 0),
            $feedbackCounts->get(4, 0),
            $feedbackCounts->get(5, 0),
        ];

        // === 7. ASAL INSTANSI PERMOHONAN ===
        $asalInstansi = PermohonanData::groupBy('asal')
            ->select('asal', DB::raw('count(*) as count'))
            ->whereNotNull('asal')
            ->pluck('count', 'asal');

        // === 8. Perbandingan Ditolak ===
        $ditolakBulanIni = PermohonanData::where('status', 'ditolak')
            ->whereYear('updated_at', now()->year)
            ->whereMonth('updated_at', now()->month)
            ->count();

        $ditolakBulanLalu = PermohonanData::where('status', 'ditolak')
            ->whereYear('updated_at', now()->subMonth()->year)
            ->whereMonth('updated_at', now()->subMonth()->month)
            ->count();

        $perbandinganDitolakLabels = [now()->subMonth()->translatedFormat('F Y'), now()->translatedFormat('F Y')];
        $perbandinganDitolakData = [$ditolakBulanLalu, $ditolakBulanIni];

        $asalLabels = $asalInstansi->keys()->map(fn($asal) => ucfirst($asal));
        $asalData = $asalInstansi->values();

        $data = compact(
            'trenLabels',
            'trenData',
            'statusLabels',
            'statusData',
            'rataWaktu',
            'jenisDataLabels',
            'jenisDataCount',
            'topUserLabels',
            'topUserCount',
            'feedbackLabels',
            'feedbackData',
            'perbandinganDitolakLabels',
            'perbandinganDitolakData',
            'asalLabels',
            'asalData'
        );


        if (auth()->user()->role === 'kepala') {
            return view('kepala.statistik', $data);
        }

        return view('admin.statistik', $data);
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
