<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'permohonan_id' => 'required|exists:data_permohonan,id',
        'pesan' => 'nullable|string|max:1000',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    Feedback::create([
        'user_id' => auth()->id(),
        'permohonan_id' => $request->permohonan_id,
        'pesan' => $request->pesan,
        'rating' => $request->rating,
    ]);

    return back()->with('success', 'Feedback berhasil dikirim.');
}
    public function updateEvaluasi(Request $request, $id)
    {
        $request->validate([
            'catatan_evaluasi' => 'required|string|min:10',
        ]);

        $feedback = Feedback::findOrFail($id);

        $feedback->catatan_evaluasi = $request->catatan_evaluasi;
        $feedback->save();

        return redirect()->back()->with('success', 'Catatan evaluasi berhasil disimpan!');
    }

}
