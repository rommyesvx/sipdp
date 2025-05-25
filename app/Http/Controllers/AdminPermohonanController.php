<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PermohonanData;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Mail\PermohonanSelesai;

class AdminPermohonanController extends Controller
{
    public function index()
    {
        $permohonans = PermohonanData::with('user')->latest()->get();
        return view('admin.permohonan.index', compact('permohonans'));
    }

    public function show($id)
    {
        $permohonan = PermohonanData::findOrFail($id);
        return view('admin.permohonan.show', compact('permohonan'));
    }


    public function update(Request $request, $id)
    {
        $permohonan = PermohonanData::findOrFail($id);
        $permohonan->status = $request->status;

        if ($request->status === 'ditolak') {
            $permohonan->alasan_penolakan = $request->alasan_penolakan;
        } else {
            $permohonan->alasan_penolakan = null;
        }

        if ($request->hasFile('file_hasil')) {
            $file = $request->file('file_hasil')->store('hasil_permohonan');
            $permohonan->file_hasil = $file;
        }

        $permohonan->save();

        activity('permohonan_data')
            ->performedOn($permohonan)
            ->causedBy(auth()->user())
            ->withProperties([
                'nomor' => $permohonan->user->no_hp,
                'pesan' => 'Permohonan data Anda telah selesai. Silakan login untuk mengunduh hasilnya.'
            ])
            ->log('Notifikasi WhatsApp berhasil dikirim.');

        if ($permohonan->status === 'selesai') {
            // $this->sendWaNotification($permohonan);

            return back()->with('error', 'Permohonan sudah selesai dan tidak dapat diubah.');
        }

        return redirect()->back()->with('success', 'Permohonan diperbarui');
    }


    public function downloadHasilPengantar($id)
    {
        $permohonan = PermohonanData::findOrFail($id);

        if (auth()->id() !== $permohonan->user_id) {
            abort(403, 'Unauthorized');
        }

        if (!$permohonan->file_permohonan || !Storage::exists($permohonan->file_permohonan)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::download($permohonan->file_permohonan);
    }

    public function downloadHasil($id)
    {
        $permohonan = PermohonanData::findOrFail($id);

        if (auth()->id() !== $permohonan->user_id) {
            abort(403, 'Unauthorized');
        }

        if (!$permohonan->file_hasil || !Storage::exists($permohonan->file_hasil)) {
            abort(404, 'File tidak ditemukan');
        }
        activity()
            ->causedBy(auth()->user())
            ->performedOn($permohonan)
            ->withProperties([
                'file' => $permohonan->file_hasil,
            ])
            ->log('User mendownload file hasil permohonan.');

        return Storage::download($permohonan->file_hasil);
    }

    protected function sendWaNotification($permohonan)
    {
        $nomor = $permohonan->user->no_hp;
        $name = $permohonan->user->name;
        $message = "Permohonan data Anda telah selesai. Silakan login untuk mengunduh hasilnya.";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://bkpsdm.blitarkota.go.id/siap-api/index.php/wa_seleksi_jpt',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'nomor' => $nomor,
                'nip' => $name,
                'message' => $message
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . env('WA_API_AUTH'),
                'Cookie: ci_session=' . env('WA_API_COOKIE')
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::error("WA Notification Error: " . $err);
        } else {
            Log::info("WA Notification Sent: " . $response);
            Log::info("Sending WA", [
                'nomor' => $nomor,
                'message' => $message,
                'response' => $response,
            ]);
        }
    }
}
