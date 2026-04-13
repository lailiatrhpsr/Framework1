<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function dashboard()
    {
        $vendors = Vendor::all();
        return view('admin.vendor.dashboard', compact('vendors'));
    }

    public function vendorDashboard($id_vendor)
    {
        $vendor = Vendor::findOrFail($id_vendor);
        $menus = Menu::where('id_vendor', $id_vendor)->get();
        $pesanan = Pesanan::with(['details.menu' => function($q) use ($id_vendor) {
            $q->where('id_vendor', $id_vendor);
        }])->whereHas('details.menu', function($q) use ($id_vendor) {
            $q->where('id_vendor', $id_vendor);
        })->get();

        return view('admin.vendor.vendordashboard', compact('vendor','menus','pesanan'));
    }

    // Tampilkan semua menu milik vendor
    public function menus($id_vendor)
    {
        $vendor = Vendor::findOrFail($id_vendor);   
        $menus = Menu::where('id_vendor', $id_vendor)->get();

        return view('admin.vendor.menus.index', compact('menus', 'vendor'));
    }

    // Form tambah menu
    public function createMenu($id_vendor)
    {
        $vendor = Vendor::findOrFail($id_vendor);
        return view('admin.vendor.menus.create', compact('vendor'));
    }

    // Simpan menu baru
    public function storeMenu(Request $request, $id_vendor)
    {
        $request->validate([
            'nama_menu'  => 'required|string|max:255',
            'harga' => 'required|numeric',
            'path_gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('path_gambar')) {
            $path = $request->file('path_gambar')->store('menus', 'public');
        } else {
            $path = null;
        }

        Menu::create([
            'id_vendor' => $id_vendor,
            'nama_menu'      => $request->nama_menu,
            'harga'     => $request->harga,
            'path_gambar' => $path,
        ]);

        return redirect()->route('vendor.menus.index', $id_vendor)->with('success', 'Menu berhasil ditambahkan');
    }

    // Lihat pesanan yang masuk ke vendor
    public function pesanan($id_vendor)
    {
        $pesanan = Pesanan::whereHas('details', function($q) use ($id_vendor) {
            $q->where('id_vendor', $id_vendor);
        })->with(['details' => function($q) use ($id_vendor) {
            $q->where('id_vendor', $id_vendor)->with('menu');
        }])->get();

        return view('vendor.pesanan.index', compact('pesanan','id_vendor'));
    }

    public function showPesanan($id_vendor, $id_pesanan)
    {
        $pesanan = Pesanan::with('details.menu')
            ->where('id_pesanan', $id_pesanan)
            ->whereHas('details.menu', function($q) use ($id_vendor) {
                $q->where('id_vendor', $id_vendor);
            })
            ->firstOrFail();

        return view('admin.vendor.pesanan.show', compact('pesanan','id_vendor'));
    }

    // Lihat pesanan lunas
    public function pesananLunas($id_vendor)
    {
        $pesanan = Pesanan::with(['details.menu' => function($q) use ($id_vendor) {
            $q->where('id_vendor', $id_vendor);
        }])->where('status', 'paid')
          ->whereHas('details.menu', function($q) use ($id_vendor) {
              $q->where('id_vendor', $id_vendor);
          })->get();

        return view('admin.vendor.pesanan.lunas', compact('pesanan', 'id_vendor'));
    }
}
