<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanData;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPermohonanExport;
use Illuminate\Support\Str;

class GenerateLaporanController extends Controller
{
    public function index(Request $request)
    {
        $allPermohonan = PermohonanData::where('jenis_data', 'like', '[%')->get();
        $kriteriaOptions = [];
        foreach ($allPermohonan as $permohonan) {
            if (Str::isJson($permohonan->jenis_data)) {
                $kriteriaList = json_decode($permohonan->jenis_data, true);
                foreach ($kriteriaList as $item) {
                    if (isset($item['kriteria'])) {
                        $kriteriaOptions[] = $item['kriteria'];
                    }
                }
            }
        }
        $kriteriaOptions = array_unique($kriteriaOptions);
        sort($kriteriaOptions);

        $query = PermohonanData::with('user');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }
        if ($request->filled('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('kriteria') && $request->kriteria != 'semua') {
            $query->where('jenis_data', 'like', '%"kriteria":"' . $request->kriteria . '"%');
        }

        $permohonans = $query->latest()->paginate(20)->withQueryString();

        return view('kepala.laporan.index', compact('permohonans', 'kriteriaOptions'));
    }

    public function export(Request $request)
    {
        $query = PermohonanData::with('user');
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }
        if ($request->filled('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        $dataLaporan = $query->latest()->get();

        $tipe = $request->query('tipe');
        $fileName = 'laporan-permohonan-' . now()->format('d-m-Y');

        if ($tipe == 'excel') {
            return Excel::download(new LaporanPermohonanExport($dataLaporan), $fileName . '.xlsx');
        }

        if ($tipe == 'pdf') {
            $pdf = Pdf::loadView('kepala.laporan.pdf.laporan_permohonan', ['permohonans' => $dataLaporan]);
            return $pdf->setPaper('a4', 'landscape')->download($fileName . '.pdf');
        }

        return redirect()->back()->with('error', 'Tipe file tidak valid.');
    }
}
