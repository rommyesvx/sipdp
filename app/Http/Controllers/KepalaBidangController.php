<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanData;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class KepalaBidangController extends Controller
{
    public function dashboard()
    {
        $jumlahEskalasi = PermohonanData::where('status', 'eskalasi')->count();
        $jumlahDisetujui = PermohonanData::where('status', 'selesai')->count();
        $jumlahDitolak = PermohonanData::where('status', 'ditolak')->count();
        $permohonanEskalasiTerbaru = PermohonanData::with('user')
            ->where('status', 'eskalasi')
            ->orderBy('updated_at', 'desc')
            ->take(5) // Ambil 5 yang paling baru
            ->get();
        $aktivitasTerkini = Activity::where('causer_id', Auth::id())
            ->where(function ($query) {
                // Hanya ambil log yang deskripsinya relevan dengan perubahan status
                $query->where('description', 'like', '%Status permohonan%')
                    ->orWhere('description', 'like', '%disetujui%')
                    ->orWhere('description', 'like', '%ditolak%')
                    ->orWhere('description', 'like', '%updated%');
            })
            ->latest()
            ->take(5)
            ->get();


        // Kirim semua data yang sudah diolah ke view
        return view('kepala.dashboard', compact(
            'jumlahEskalasi',
            'jumlahDisetujui',
            'jumlahDitolak',
            'permohonanEskalasiTerbaru',
            'aktivitasTerkini'
        ));
    }
}
