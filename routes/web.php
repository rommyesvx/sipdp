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
use App\Http\Controllers\StatistikPegawaiController;
use App\Http\Controllers\ChatController;

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');


Route::get('/', [UserDashboardController::class, 'index'])->name('dashboardUser');

Route::get('/faq', [UserController::class, 'showFaqUser'])->name('tampilFaq');
Route::get('/kontak-kami', [UserController::class, 'showKontakKami'])->name('contactus');


// == 2. RUTE UNTUK SEMUA PENGGUNA YANG SUDAH LOGIN (Role Apapun) ==
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Alur Permohonan oleh Pengguna
    Route::get('/permohonan/ajukan', [RequestDataController::class, 'showRequestForm'])->name('permohonan.form');
    Route::post('/permohonan/simpan', [RequestDataController::class, 'store'])->name('permohonan.store');
    Route::get('/riwayat', [RiwayatPermohonanController::class, 'riwayat'])->name('users.riwayat');
    Route::get('/riwayat/{id}', [RiwayatPermohonanController::class, 'show'])->name('permohonan.show');
    Route::get('/riwayat/{id}/download-pengantar', [RiwayatPermohonanController::class, 'downloadHasilPengantar'])->name('permohonan.downloadPengantar');
    Route::get('/riwayat/{id}/download-hasil', [RiwayatPermohonanController::class, 'downloadHasil'])->name('permohonan.downloadHasil');
    Route::get('/permohonan/{id}/ajukan-ulang', [RequestDataController::class, 'ajukanUlang'])->name('permohonan.ajukanUlang');

    // Feedback dari Pengguna
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    // Notifikasi (bisa diakses admin & kepala bidang)
    Route::get('/notifikasi/{id}/baca', [NotificationController::class, 'markAsRead'])->name('admin.notifikasi.baca');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'markAllAsRead'])->name('admin.notifikasi.bacaSemua');
Route::get('/chat/preview', [ChatController::class, 'preview'])->name('user.chat.preview');
    Route::get('/chat/{id}', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/{id}', [ChatController::class, 'store'])->name('chat.store');

    Route::get('/chat/{id}/messages', [ChatController::class, 'fetchMessages'])
        ->name('chat.fetch');
    Route::post('/chat/{id}/send', [ChatController::class, 'store'])
        ->name('chat.send');

    

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
        Route::get('/statistik-pegawai', [StatistikPegawaiController::class, 'index'])->name('statistik-pegawai.index');

        Route::get('/permohonan', [AdminPermohonanController::class, 'index'])->name('permohonan.index');
        Route::get('/permohonan/{id}', [AdminPermohonanController::class, 'show'])->name('permohonan.show');
        Route::post('/permohonan/{id}', [AdminPermohonanController::class, 'update'])->name('permohonan.update');
        Route::get('/permohonan/{id}/export', [AdminPermohonanController::class, 'export'])->name('permohonan.export');
        Route::get('/permohonan/{id}/download-surat', [AdminPermohonanController::class, 'downloadHasilPengantar'])->name('permohonan.downloadSurat');
        Route::get('/permohonan/{id}/download-hasil', [AdminPermohonanController::class, 'downloadHasil'])->name('permohonan.downloadHasil');
        Route::post('/permohonan/{id}/generate-attach', [AdminPermohonanController::class, 'generateAndAttach'])->name('permohonan.generateAttach');

        Route::get('/feedback', [PegawaiController::class, 'feedbackIndex'])->name('feedback.index');
        Route::post('/feedback/{id}/evaluasi', [FeedbackController::class, 'updateEvaluasi'])->name('feedback.updateEvaluasi');

        Route::get('/tingkat-pendidikan/{periodeTerbaru?}', [PegawaiController::class, 'tingkatPendidikan'])->name('tingkatpendidikan');
        Route::get('/agama/{periodeTerbaru?}', [PegawaiController::class, 'agama'])->name('agama');
        Route::get('/jeniskelamin/{periodeTerbaru?}', [PegawaiController::class, 'jeniskelamin'])->name('jeniskelamin');

        Route::get('/data-pegawai/export/excel', [DataPegawaiController::class, 'exportExcel'])->name('dataPegawai.exportExcel');
        Route::get('/data-pegawai/export/pdf', [DataPegawaiController::class, 'exportPdf'])->name('dataPegawai.exportPdf');

        Route::get('/livechat/unread-count', [ChatController::class, 'unreadCount'])
            ->name('chat.unreadCount');
        Route::get('/livechat', [ChatController::class, 'adminIndex'])->name('chat.index');
        Route::get('/livechat/{id}', [ChatController::class, 'adminRoom'])->name('chat.room');
        Route::get('/livechat/{id}/messages', [ChatController::class, 'fetchMessages'])
            ->name('chat.fetch');
        Route::get('/livechat/rooms', [ChatController::class, 'fetchRooms'])
            ->name('chat.rooms');
        
    });


// == 4. RUTE KHUSUS KEPALA BIDANG ==
Route::prefix('kepala')
    ->middleware(['auth', 'peran:kepala'])
    ->name('kepala.')
    ->group(function () {

        Route::get('/dashboard', [KepalaBidangController::class, 'dashboard'])->name('dashboard');

        Route::get('/statistik', [PegawaiController::class, 'statistik'])->name('statistik');
        Route::get('/statistik-pegawai', [StatistikPegawaiController::class, 'index'])->name('statistik-pegawai.index');


        Route::get('/permohonan', [KepalaPermohonanController::class, 'index'])->name('permohonan.index');
        Route::get('/permohonan/semua', [KepalaPermohonanController::class, 'semuaPermohonan'])->name('permohonan.semua');
        Route::get('/permohonan/{id}', [KepalaPermohonanController::class, 'show'])->name('permohonan.show');
        Route::post('/permohonan/{id}', [KepalaPermohonanController::class, 'update'])->name('permohonan.update');

        Route::get('/permohonan/{id}/download-pengantar', [KepalaPermohonanController::class, 'downloadSurat'])->name('permohonan.downloadPengantar');

        Route::get('/laporan', [GenerateLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [GenerateLaporanController::class, 'export'])->name('laporan.export');

        Route::get('/feedback', [PegawaiController::class, 'feedbackIndex'])->name('feedback.index');
    });


// == 5. RUTE AUTENTIKASI DARI BREEZE ==
require __DIR__ . '/auth.php';
