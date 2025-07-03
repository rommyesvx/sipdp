<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\PermohonanData;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
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

        // === Jenis Kelamin ===
        // $jumlah_laki = Pegawai::where('jenisKelamin', 'M')->where('periode_data', $periodeTerbaru)->count();
        // $jumlah_perempuan = Pegawai::where('jenisKelamin', 'F')->where('periode_data', $periodeTerbaru)->count();

        //Agama
        // $agamaData = Pegawai::where('periode_data', $periodeTerbaru)
        //     ->select('agama')
        //     ->get()
        //     ->groupBy('agama')
        //     ->map(fn($group) => $group->count());

        // === Tingkat Pendidikan ===
        // $pendidikanData = Pegawai::whereNotNull('tkPendidikanTerakhir')
        //     ->where('periode_data', $periodeTerbaru)
        //     ->select('tkPendidikanTerakhir')
        //     ->get()
        //     ->groupBy('tkPendidikanTerakhir')
        //     ->map(fn($group) => $group->count());


        $totalBulanIni = PermohonanData::whereMonth('created_at', now()->month)->count();
        $selesaiBulanIni = PermohonanData::where('status', 'selesai')->whereMonth('created_at', now()->month)->count();
        $persentaseSelesai = $totalBulanIni > 0 ? round(($selesaiBulanIni / $totalBulanIni) * 100) : 0;

        // $bulanIni = Carbon::now()->month;
        // $permohonanBulanIni = PermohonanData::whereMonth('created_at', $bulanIni)->count();
        // $permohonanSelesai = PermohonanData::whereMonth('created_at', $bulanIni)->where('status', 'selesai')->count();

        // $persentasePelayanan = $permohonanBulanIni > 0
        //     ? round(($permohonanSelesai / $permohonanBulanIni) * 100, 2)
        //     : 0;


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

    $feedbacks = Feedback::with(['user', 'permohonan'])
                        ->orderBy($sort, $direction)
                        ->paginate(10); 

    if ($user -> role == 'admin') {
        return view('admin.feedback', [
            'feedbacks' => $feedbacks,
            'sort' => $sort,
            'direction' => $direction
        ]);
    }

    if ($user-> role == 'kepala') {
        return view('kepala.feedback', [
            'feedbacks' => $feedbacks,
            'sort' => $sort,
            'direction' => $direction
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
        
        // 2. Loop melalui setiap baris data yang diambil
        foreach ($allJenisData as $jsonData) {
            // Hanya proses jika data adalah JSON yang valid (dari filter builder)
            if (\Illuminate\Support\Str::isJson($jsonData)) {
                $kriteriaList = json_decode($jsonData, true);
                if (is_array($kriteriaList)) {
                    // 3. Loop melalui setiap kriteria di dalam JSON
                    foreach ($kriteriaList as $item) {
                        if (isset($item['kriteria'])) {
                            $kriteriaName = $item['kriteria'];
                            // Tambahkan atau +1 counter untuk kriteria tersebut
                            if (!isset($kriteriaCounts[$kriteriaName])) {
                                $kriteriaCounts[$kriteriaName] = 0;
                            }
                            $kriteriaCounts[$kriteriaName]++;
                        }
                    }
                }
            }
        }
        
        // 4. Urutkan kriteria dari yang paling banyak diminta
        arsort($kriteriaCounts);
        
        // 5. Ambil 5 teratas
        $topKriteria = array_slice($kriteriaCounts, 0, 5, true);
        
        // 6. Siapkan data final untuk dikirim ke chart
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

        // Kirim semua data yang sudah diolah ke view
        return view('admin.statistik', compact(
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
            'feedbackData'
        ));
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
