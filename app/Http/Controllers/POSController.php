<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index() {
        return view('admin.pos'); 
    }

    public function cekBarang($kode) {

        $barang = Barang::where('kode_barang', $kode)->first();

        if ($barang) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $barang->id_barang,
                    'nama' => $barang->nama_barang,
                    'harga' => $barang->harga
                ]
            ]); 
        }
        return response()->json(['status' => 'error'], 404);
    }

    public function simpan(Request $request)
    {
        // Menggunakan Transaction agar jika satu gagal, semua dibatalkan
        DB::beginTransaction();

        try {
            // 1. Simpan data ke tabel penjualan [cite: 357-359]
            $penjualan = new Penjualan();
            $penjualan->total = $request->total;
            $penjualan->timestamp = now(); // Sesuai kolom timestamp di migration
            $penjualan->save();

            // 2. Simpan rincian ke tabel penjualan_detail [cite: 354-356]
            foreach ($request->items as $item) {
                $detail = new PenjualanDetail();
                $detail->id_penjualan = $penjualan->id_penjualan;
                $detail->id_barang = $item['id_barang'];
                $detail->jumlah = $item['qty']; // Pastikan index ini sama dengan yang di JS (qty)
                $detail->subtotal = $item['subtotal'];
                $detail->save();
            }

            DB::commit();
            
            // Response sukses sesuai standar modul 
            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            DB::rollback();
            // Mengembalikan error agar bisa dibaca di console browser
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
