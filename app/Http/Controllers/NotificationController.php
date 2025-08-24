<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanData;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $permohonan = PermohonanData::findOrFail($id);

        if (is_null($permohonan->admin_read_at)) {
            $permohonan->admin_read_at = now();
            $permohonan->save();
        }

        return redirect()->route('admin.permohonan.show', $permohonan->id);
    }
    public function markAllAsRead()
    {
        
        PermohonanData::where(function($query) {
                $query->where('status', 'diajukan')
                      ->orWhere('status', 'eskalasi');
            })
            ->whereNull('admin_read_at')
            ->update(['admin_read_at' => now()]); 

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
