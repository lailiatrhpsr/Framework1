<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Toko;
use App\Models\Kunjungan;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class KunjunganController extends Controller
{
    public function index() {
        $history = Kunjungan::with('toko')->latest()->get();
    return view('admin.kunjungan', compact('history'));
    }

    public function cekToko($barcode) {
        $toko = Toko::where('barcode', $barcode)->first();

        if (!$toko) {
            return response()->json([
                'success' => false,
                'message' => 'Barcode toko tidak terdaftar di sistem!'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'nama_toko' => $toko->nama_toko,
            'latitude' => $toko->latitude,
            'longitude' => $toko->longitude,
            'accuracy' => $toko->accuracy
        ]);
    }
    public function store(Request $request) {
        // 1. Validasi input dari request AJAX/Fetch
        $request->validate([
            'barcode' => 'required|string',
            'lat_sales' => 'required|numeric',
            'lng_sales' => 'required|numeric',
            'accuracy_sales' => 'required|numeric',
        ]);

        // 2. Cari data toko berdasarkan string 'barcode' hasil scan 
        $toko = Toko::where('barcode', $request->barcode)->first();

        // Jika barcode tidak ditemukan di database
        if (!$toko) {
            return response()->json([
                'success' => false,
                'message' => 'Barcode toko tidak terdaftar dalam sistem!'
            ], 404);
        }

        // 3. Hitung jarak aktual posisi sales ke lokasi toko menggunakan rumus Haversine [cite: 14, 18]
        $jarakAktual = $this->haversine(
            $toko->latitude, $toko->longitude,
            $request->lat_sales, $request->lng_sales
        );

        // 4. Hitung batas toleransi jarak (Threshold Efektif) [cite: 89]
        // Formula: Batas standar (300m) + akurasi koordinat toko + akurasi koordinat sales [cite: 89]
        $thresholdEfektif = 300 + $toko->accuracy + $request->accuracy_sales;

        $status = $jarakAktual <= $thresholdEfektif ? "DITERIMA" : "DITOLAK";

        $kunjungan = Kunjungan::create([
            'toko_id' => $toko->id, // Menggunakan ID auto-increment dari tabel toko
            'lat_sales' => $request->lat_sales,
            'lng_sales' => $request->lng_sales,
            'accuracy_sales' => $request->accuracy_sales,
            'status' => $status
        ]);

        // 6. Return response dalam bentuk JSON untuk diolah oleh JavaScript di View [cite: 40]
        return response()->json([
            'success' => true,
            'status' => $status,
            'jarak' => round($jarakAktual, 2), // Pembulatan 2 angka di belakang koma (meter)
            'threshold' => round($thresholdEfektif, 2),
            'nama_toko' => $toko->nama_toko,
            'Waktu' => $kunjungan->created_at->format('d-m-Y H:i:s')
        ]);
    }

    private function haversine($lat1, $lng1, $lat2, $lng2) {
        $R = 6371000; 
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $R * $c; 
    }

    public function Tokoindex() {
        $allToko = Toko::all();
        return view('admin.toko.index', compact('allToko'));
    }

    public function cetakQr($barcode) {
        // 1. Cari data toko berdasarkan barcode
        $toko = Toko::where('barcode', $barcode)->firstOrFail();

        // 2. PERBAIKAN: Gunakan 'new QrCode' langsung (Sintaks Endroid v5/v6)
        $qrCode = new QrCode(
            data: $toko->barcode,
            encoding: new Encoding('UTF-8'),
            size: 300,
            margin: 10,
            foregroundColor: new Color(0, 0, 0),       // Warna Hitam
            backgroundColor: new Color(255, 255, 255)  // Warna Putih
        );

        // 3. Render objek menjadi file gambar PNG
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // 4. Download file gambar langsung ke browser admin
        return response($result->getString())
                ->header('Content-Type', $result->getMimeType())
                ->header('Content-Disposition', 'attachment; filename="QR_'.$toko->nama_toko.'.png"');
    }

    public function create() {
        return view('admin.toko.create');
    }

    public function Tokostore(Request $request) {
        $request->validate([
            'barcode' => 'required|string|unique:toko,barcode', 
            'nama_toko' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'required|numeric',
        ]);

        // Simpan data ke database
        Toko::create([
            'barcode' => $request->barcode,
            'nama_toko' => $request->nama_toko,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy' => $request->accuracy,
        ]);

        // Kembalikan ke halaman list toko dengan pesan sukses
        return redirect()->route('toko.index')->with('success', 'Toko baru berhasil didaftarkan!');
    }
}