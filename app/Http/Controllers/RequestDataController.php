<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanData;
use Illuminate\Support\Facades\Auth;

class RequestDataController extends Controller
{
    public function showRequestForm()
    {
        return view('users.requestData');
    }
    public function store(Request $request)
    {
         //dd($request->all());
        $request->validate([
            'tujuan' => 'required|string',
            'tipe' => 'required|string',
            'jenis_data' => 'required|string',
            'file_permohonan' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'catatan' => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('file_permohonan')) {
            $path = $request->file('file_permohonan')->store('permohonan_files', 'public');
        }

        PermohonanData::create([
            'user_id' => Auth::id(), // Ambil ID user dari sesi login
            'tujuan' => $request->input('tujuan'),
            'tipe' => $request->input('tipe'),
            'jenis_data' => $request->input('jenis_data'),
            'file_permohonan' => $path,
            'catatan' => $request->input('catatan'),
        ]);
        session()->flash('success', 'Permohonan berhasil dikirim!');

        return redirect()->back()->with('success', 'Permohonan berhasil dikirim!');
    }
}
