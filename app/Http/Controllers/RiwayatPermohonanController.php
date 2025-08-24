<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\PermohonanData;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class RiwayatPermohonanController extends Controller
{
    public function riwayat(Request $request)
    {
        $user = Auth::user();

        $sortableColumns = ['created_at', 'status'];
        $sort = in_array($request->query('sort'), $sortableColumns) ? $request->query('sort') : 'created_at';
        $direction = in_array($request->query('direction'), ['asc', 'desc']) ? $request->query('direction') : 'desc';

        $permohonans = PermohonanData::where('user_id', $user->id)
            ->orderBy($sort, $direction)
            ->paginate(10);

        return view('users.riwayat', [
            'permohonans' => $permohonans,
            'sort' => $sort,
            'direction' => $direction
        ]);
    }
    public function show($id)
    {
        $permohonan = PermohonanData::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('users.detailPermohonan', compact('permohonan'));
    }
    public function downloadHasilPengantar($id)
    {
        $permohonan = PermohonanData::findOrFail($id);

        if (auth()->id() !== $permohonan->user_id) {
            abort(403, 'Unauthorized');
        }
        $filePengantar = $permohonan->suratPengantar;

        if (!$filePengantar || !Storage::disk('public')->exists($filePengantar->path)) {
            return back()->with('error', 'File surat pengantar tidak ditemukan.');
        }

        return Storage::disk('public')->response($filePengantar->path, $filePengantar->nama_asli_file);
    }
    public function downloadHasil($id)
    {
        $permohonan = PermohonanData::findOrFail($id);

        if (auth()->id() !== $permohonan->user_id) {
            abort(403, 'Akses Ditolak');
        }

        $fileHasil = $permohonan->fileHasil;

        if (!$fileHasil || !Storage::disk('public')->exists($fileHasil->path)) {
            return back()->with('error', 'File hasil tidak ditemukan atau belum diunggah oleh admin.');
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($permohonan)
            ->log('Pengguna mengunduh file hasil.');

        return Storage::disk('public')->response($fileHasil->path, $fileHasil->nama_asli_file);
    }
}
