<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BukuController; 
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\UndanganController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\VendorController;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Http\Controllers\MenuController;
use App\Http\Controllers\PesananController;

// ----------------------
// ROUTE UNTUK CUSTOMER
// ----------------------
Route::get('/menus', [MenuController::class, 'index'])->name('customer.menus');
Route::get('/menu/{vendor}', [MenuController::class, 'showByVendor'])->name('customer.menu');
Route::post('/cart/add', [PesananController::class, 'addToCart'])->name('customer.cart.add');
Route::get('/cart', [PesananController::class, 'cart'])->name('customer.cart');
Route::post('/cart/update/{id_menu}', [PesananController::class, 'updateCart'])->name('customer.cart.update');
Route::post('/cart/delete/{id_menu}', [PesananController::class, 'deleteFromCart'])->name('customer.cart.delete');
Route::get('/checkout', [PesananController::class, 'showCheckout'])->name('customer.checkout.show');
Route::post('/checkout', [PesananController::class, 'checkout'])->name('customer.checkout');
Route::post('/midtrans/notification', [PesananController::class, 'notificationHandler'])->name('customer.midtrans.notification');
Route::post('/midtrans/notification', [PesananController::class, 'notificationHandler']);
Route::post('/payment/callback', [PesananController::class, 'callback'])->name('customer.payment.callback');
Route::get('/pesanan/{id}', [PesananController::class, 'show'])->name('customer.pesanan.show');

// Halaman utama customer
Route::get('/', function () {
    return redirect()->route('customer.menus'); 
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


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
    Route::get('/sertifikat/download/{jenis}', [SertifikatController::class, 'download'])->name('sertifikat.download');

    // Rute khusus Undangan
    Route::get('/undangan', [UndanganController::class, 'index'])->name('undangan.index');
    Route::get('/undangan/download/{jenis}', [UndanganController::class, 'download'])->name('undangan.download');

    // Kategori
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');

    // Buku
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');

    // Barang
    Route::get('/barang/html', [BarangController::class, 'html'])->name('barang.html');
    Route::get('/barang/datatables', [BarangController::class, 'datatables'])->name('barang.datatables');
    Route::post('/barang/cetak', [BarangController::class, 'cetakLabel'])->name('barang.cetak');
    Route::get('/barang/label', [BarangController::class, 'labelIndex'])->name('barang.label');
    Route::resource('barang', BarangController::class);

    Route::get('/kota', function () {
        return view('admin.kota');
    })->name('kota.index');

    Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah.index');
    Route::get('/provinsi', [WilayahController::class, 'provinsi'])->name('wilayah.provinsi');
    Route::get('/kota/{provinsi_id}', [WilayahController::class, 'kota'])->name('wilayah.kota');
    Route::get('/kecamatan/{kota_id}', [WilayahController::class, 'kecamatan'])->name('wilayah.kecamatan');
    Route::get('/kelurahan/{kecamatan_id}', [WilayahController::class, 'kelurahan'])->name('wilayah.kelurahan');

    // Halaman utama kasir
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::get('/pos/cek-barang/{kode}', [POSController::class, 'cekBarang']);
    Route::post('/pos/simpan', [POSController::class, 'simpan'])->name('pos.simpan');

    // Rute untuk vendor
        Route::get('dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');
        Route::get('/vendors/{id_vendor}/dashboard', [VendorController::class, 'vendorDashboard'])->name('vendor.vendordashboard');
        Route::get('/vendor/{id_vendor}/menus', [VendorController::class, 'menus'])->name('vendor.menus.index');
        Route::get('/vendor/{id_vendor}/menus/create', [VendorController::class, 'createMenu'])->name('vendor.menus.create');
        Route::post('/vendor/{id_vendor}/menus', [VendorController::class, 'storeMenu'])->name('vendor.menus.store');
        Route::get('/vendor/{id_vendor}/pesanan', [VendorController::class, 'pesanan'])->name('vendor.pesanan.index');
        Route::get('/vendor/{id_vendor}/pesanan/{id}', [VendorController::class, 'showPesanan'])->name('vendor.pesanan.show');
        Route::get('/vendor/{id_vendor}/pesanan/lunas', [VendorController::class, 'pesananLunas'])->name('vendor.pesanan.lunas');
});
