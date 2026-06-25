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
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\VendorController;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Http\Controllers\MenuController;
use App\Http\Controllers\PesananController;

use App\Http\Controllers\CustomerController;

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
Route::get('/pesanan', [PesananController::class, 'index'])->name('customer.pesanan.index');


Route::get('/tes-db', function() {
    return response()->json(\App\Models\Antrian::all());
});

// --------------------------------------------------
// ROUTE SISTEM ANTRIAN DIGITAL REAL-TIME (SSE)
// --------------------------------------------------

// ─────────────────────────────────────────────
//  Views
// ─────────────────────────────────────────────
Route::get('/guest',  [AntrianController::class, 'guest']);
Route::get('/admin',  [AntrianController::class, 'admin']);
Route::get('/papan',  [AntrianController::class, 'papan']);

// ─────────────────────────────────────────────
//  SSE stream (GET, tanpa CSRF)
// ─────────────────────────────────────────────
Route::get('/sse/antrian', [AntrianController::class, 'stream'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// ─────────────────────────────────────────────
//  API JSON
// ─────────────────────────────────────────────
Route::prefix('antrian')->group(function () {
    // Guest: daftar antrian
    Route::post('/daftar',              [AntrianController::class, 'daftar']);

    // Admin: aksi
    Route::post('/panggil',             [AntrianController::class, 'panggil']);
    Route::patch('/{antrian}/selesai', [AntrianController::class, 'selesai']);
    Route::patch('/{antrian}/terlewat', [AntrianController::class, 'terlewat']);
    Route::patch('/{antrian}/ulang',    [AntrianController::class, 'panggilUlang']);

    // Admin: baca data (fallback jika SSE putus)
    Route::get('/data',                 [AntrianController::class, 'data']);
});


Route::get('/absensi-scanner', [AbsensiController::class, 'index']);
Route::post('/absensi-scan', [AbsensiController::class, 'store']);

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
    Route::get('/barang/scanner', [BarangController::class, 'scanner'])->name('barang.scanner');
    Route::get('/barang/get/{id}', [BarangController::class, 'getBarang']);
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
        Route::get('/vendor/scanner', [VendorController::class, 'scanner'])->name('vendor.scanner');
        Route::get('/vendor/pesanan/{id}', [VendorController::class, 'getPesanan'])->name('vendor.getPesanan');


    // route untuk customer
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.data.index');
    Route::get('/customer/create-blob', [CustomerController::class, 'createBlob'])->name('customer.create.blob');
    Route::post('/customer/store-blob', [CustomerController::class, 'storeBlob'])->name('customer.store.blob');
    Route::get('/customer/create-path', [CustomerController::class, 'createPath'])->name('customer.create.path');
    Route::post('/customer/store-path', [CustomerController::class, 'storePath'])->name('customer.store.path');     
    
    Route::get('/kunjungan', [KunjunganController::class, 'index'])->name('kunjungan.index');
    Route::get('/cek-toko/{barcode}', [KunjunganController::class, 'cekToko'])->name('kunjungan.cekToko');
    Route::post('/kunjungan/store', [KunjunganController::class, 'store'])->name('kunjungan.store');
    Route::get('/admin/toko', [KunjunganController::class, 'Tokoindex'])->name('toko.index');
    Route::get('/admin/toko/cetak-qr/{barcode}', [KunjunganController::class, 'cetakQr'])->name('toko.cetakQr');
    Route::get('/toko/create', [KunjunganController::class, 'create'])->name('toko.create');
    Route::post('/toko/store', [KunjunganController::class, 'Tokostore'])->name('toko.store');

});
