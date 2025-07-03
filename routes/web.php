<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RequestDataController;
use App\Http\Controllers\RiwayatPermohonanController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\AdminPermohonanController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DataPegawaiController;
use App\Http\Controllers\KepalaBidangController;
use App\Http\Controllers\GenerateLaporanController;
use App\Http\Controllers\KepalaPermohonanController;
use App\Http\Controllers\UserDashboardController;


// == 1. RUTE PUBLIK (Bisa diakses siapa saja tanpa login) ==
Route::get('/', function () {
    return view('users.newIndexUser');
})->name('welcome');

Route::get('/dashboard-user', [UserDashboardController::class, 'index'])->name('dashboardUser');

Route::get('/faq', [UserController::class, 'showFaqUser'])->name('tampilFaq');
Route::get('/kontak-kami', [UserController::class, 'showKontakKami'])->name('contactus');


// == 2. RUTE UNTUK SEMUA PENGGUNA YANG SUDAH LOGIN (Role Apapun) ==
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Alur Permohonan oleh Pengguna
    Route::get('/permohonan/ajukan', [RequestDataController::class, 'showRequestForm'])->name('permohonan.form');
    Route::post('/permohonan/simpan', [RequestDataController::class, 'store'])->name('permohonan.store');
    Route::get('/riwayat', [RiwayatPermohonanController::class, 'riwayat'])->name('users.riwayat');
    Route::get('/riwayat/{id}', [RiwayatPermohonanController::class, 'show'])->name('permohonan.show');
    Route::get('/riwayat/{id}/download-hasil', [RiwayatPermohonanController::class, 'downloadHasilPengantar'])->name('permohonan.downloadHasil');
    
    // Feedback dari Pengguna
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    // Notifikasi (bisa diakses admin & kepala bidang)
    Route::get('/notifikasi/{id}/baca', [NotificationController::class, 'markAsRead'])->name('admin.notifikasi.baca');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'markAllAsRead'])->name('admin.notifikasi.bacaSemua');
});


// == 3. RUTE KHUSUS ADMIN ==
Route::prefix('admin')
    ->middleware(['auth', 'peran:admin'])
    ->name('admin.')
    ->group(function () {
        
    Route::get('/', [PegawaiController::class, 'index'])->name('index');
    Route::get('/statistik', [PegawaiController::class, 'statistik'])->name('statistik');
    Route::get('/log', [ActivityLogController::class, 'index'])->name('log');
    Route::get('/datapegawai', [DataPegawaiController::class, 'index'])->name('dataPegawai.index');
    
    Route::get('/permohonan', [AdminPermohonanController::class, 'index'])->name('permohonan.index');
    Route::get('/permohonan/{id}', [AdminPermohonanController::class, 'show'])->name('permohonan.show');
    Route::post('/permohonan/{id}', [AdminPermohonanController::class, 'update'])->name('permohonan.update');
    Route::get('/permohonan/{id}/export', [AdminPermohonanController::class, 'export'])->name('permohonan.export');
    Route::get('/permohonan/{id}/download-surat', [AdminPermohonanController::class, 'downloadSuratPengantar'])->name('permohonan.downloadSurat');
    
    Route::get('/feedback', [PegawaiController::class, 'feedbackIndex'])->name('feedback.index');
    Route::post('/feedback/{id}/evaluasi', [FeedbackController::class, 'updateEvaluasi'])->name('feedback.updateEvaluasi'); 
    
    Route::get('/tingkat-pendidikan/{periodeTerbaru?}', [PegawaiController::class, 'tingkatPendidikan'])->name('tingkatpendidikan');
    Route::get('/agama/{periodeTerbaru?}', [PegawaiController::class, 'agama'])->name('agama');
    Route::get('/jeniskelamin/{periodeTerbaru?}', [PegawaiController::class, 'jeniskelamin'])->name('jeniskelamin');
});


// == 4. RUTE KHUSUS KEPALA BIDANG ==
Route::prefix('kepala')
    ->middleware(['auth', 'role:kepala_bidang'])
    ->name('kepala.')
    ->group(function () {
    
    Route::get('/dashboard', [KepalaBidangController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/permohonan', [KepalaPermohonanController::class, 'index'])->name('permohonan.index');
    Route::get('/permohonan/{id}', [KepalaPermohonanController::class, 'show'])->name('permohonan.show');
    Route::post('/permohonan/{id}', [KepalaPermohonanController::class, 'update'])->name('permohonan.update'); // Menggunakan PUT untuk update
    
    Route::get('/laporan', [GenerateLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [GenerateLaporanController::class, 'export'])->name('laporan.export');
    
    Route::get('/feedback', [PegawaiController::class, 'feedbackIndex'])->name('feedback.index');
});


// == 5. RUTE AUTENTIKASI DARI BREEZE ==
require __DIR__.'/auth.php';