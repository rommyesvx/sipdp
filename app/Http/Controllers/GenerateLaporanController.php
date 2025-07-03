<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanData;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPermohonanExport;

class GenerateLaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = PermohonanData::with('user'); // Mulai query

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }

        if ($request->filled('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        $permohonans = $query->latest()->paginate(20)->withQueryString();

        return view('kepala.laporan.index', compact('permohonans'));
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
            $pdf = Pdf::loadView('pdf.hasil_permohonan', ['permohonans' => $dataLaporan]);
            return $pdf->setPaper('a4', 'landscape')->download($fileName . '.pdf');
        }

        return redirect()->back()->with('error', 'Tipe file tidak valid.');
    }
}
