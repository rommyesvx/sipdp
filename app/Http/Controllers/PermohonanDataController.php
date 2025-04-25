<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanData;
use App\Notifications\PermohonanSelesaiNotification;

class PermohonanDataController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|in:diproses,selesai,ditolak'
        ]);

        // Cari data permohonan berdasarkan ID
        $permohonan = PermohonanData::findOrFail($id);

        // Update status
        $permohonan->status = $request->input('status');
        $permohonan->save();

        // Jika status berubah menjadi selesai, kirim notifikasi email
        if ($permohonan->status === 'selesai') {
            $permohonan->user->notify(new PermohonanSelesaiNotification($permohonan));
        }

        return redirect()->back()->with('success', 'Status permohonan berhasil diperbarui.');
    }
}
