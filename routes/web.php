<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestDataController;
use App\Http\Controllers\RiwayatPermohonanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PegawaiController;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/users/indexUser', 'users.indexUser')
    ->name('tampilLogin');




// Route::get('/profile', [ProfileController::class, 'edit'])
//     ->middleware('auth')
//     ->name('profile.edit');

Route::get('/dashboard', function () {
    return view(view: 'dashboard');
})->middleware(middleware: ['auth', 'verified'])->name(name: 'dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/users/permohonan', [RequestDataController::class, 'showRequestForm'])->name('permohonan.form');
    Route::post('/users/permohonan', [RequestDataController::class, 'store'])->name('permohonan.store');
    Route::get('/users/riwayat-permohonan', [RiwayatPermohonanController::class, 'riwayat'])->name('users.riwayat');
    Route::get('/users/detailPermohonan/{id}', [RiwayatPermohonanController::class, 'show'])->name('permohonan.show');
    Route::post('/feedback/store', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/admin/index', [PegawaiController::class, 'ambilAllData'])->name('admin.index');
    Route::get('/admin/agama/{periodeTerbaru?}', [PegawaiController::class, 'agama'])->name('admin.agama');
    Route::get('/admin/jeniskelamin/{periodeTerbaru?}', [PegawaiController::class, 'jeniskelamin'])->name('admin.jeniskelamin');
    Route::get('/admin/tingkatpendidikan/{periodeTerbaru?}', [PegawaiController::class, 'tingkatPendidikan'])->name('admin.tingkatpendidikan');
});



require __DIR__ . '/auth.php';

Route::get('/users/faq', [UserController::class, 'showFaqUser'])
    ->name('tampilFaq');
