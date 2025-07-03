<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanData; 
use App\Models\Feedback;

class UserDashboardController extends Controller
{
    public function index()
    {
  
        $totalPermohonan = PermohonanData::count();


        $permohonanSelesai = PermohonanData::where('status', 'selesai')->count();

        $rawRating = Feedback::avg('rating');
     
        $kepuasanPengguna = number_format($rawRating, 1);
        $latestFeedbacks = Feedback::with('user')
                                ->whereNotNull('pesan')
                                ->latest() 
                                ->take(3) 
                                ->get();

        return view('users.newIndexUser', [
            'totalPermohonan' => $totalPermohonan,
            'permohonanSelesai' => $permohonanSelesai,
            'kepuasanPengguna' => $kepuasanPengguna,
            'latestFeedbacks' => $latestFeedbacks
        ]);
    }
}
