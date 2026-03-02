<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BukuController; 
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\UndanganController;
use Barryvdh\DomPDF\Facade\Pdf;



Route::get('/', function () { 
    return redirect()->route('login'); 
});

Auth::routes();

Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('/otp-verify', [LoginController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/otp-verify', [LoginController::class, 'verifyOtp'])->name('otp.verify.post');

// Group routes dengan middleware auth
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');
    
    Route::get('/sertifikat', [SertifikatController::class, 'index'])->name('sertifikat.index');
    Route::get('/sertifikat/download/{judul}', [SertifikatController::class, 'download'])->name('sertifikat.download');

    // Rute khusus Undangan
    Route::get('/undangan/download', [UndanganController::class, 'download'])->name('undangan.download');

    // Kategori
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');

    // Buku
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');

    // Barang
    Route::post('/barang/cetak', [BarangController::class, 'cetakLabel'])->name('barang.cetak');
    Route::get('/barang/label', [BarangController::class, 'labelIndex'])->name('barang.label');
    Route::resource('barang', BarangController::class);



});
