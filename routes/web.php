<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BukuController; 
use App\Http\Controllers\KategoriController;

Route::get('/', function () { 
    return redirect()->route('login'); 
});

Auth::routes();

// Home & Dashboard
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Group routes dengan middleware auth
Route::middleware(['auth'])->group(function () {
    
    // Kategori
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');

    // Buku
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
});
