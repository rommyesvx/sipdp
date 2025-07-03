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
            ->paginate(5);

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

        if (!$permohonan->file_permohonan || !Storage::disk('public')->exists($permohonan->file_permohonan)) {
            return back()->with('error', 'File surat pengantar tidak ditemukan.');
        }

       return Storage::disk('public')->response($permohonan->file_permohonan);
    }
}
