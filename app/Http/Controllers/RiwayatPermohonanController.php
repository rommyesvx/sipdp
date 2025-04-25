<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\PermohonanData;
use Illuminate\Http\Request;

class RiwayatPermohonanController extends Controller
{
    public function riwayat()
    {
        $user = Auth::user();

        $permohonans = PermohonanData::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.riwayat', compact('permohonans'));
    }
    public function show($id)
    {
        $permohonan = PermohonanData::where('id', $id)
            ->where('user_id', auth()->id()) // memastikan hanya pemilik data yang bisa lihat
            ->firstOrFail();

        return view('users.detailPermohonan', compact('permohonan'));
    }
}
