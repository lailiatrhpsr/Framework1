<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Vendor;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('vendor')->get();
        return view('customer.menu', compact('menus'));
    }

    // Tampilkan menu berdasarkan vendor
    public function showByVendor($vendorId)
    {
        $menus = Menu::with('vendor')->where('id_vendor', $vendorId)->get();
        return view('customer.menu', compact('menus'));
    }

    // Form tambah menu
    public function create()
    {
        $vendors = Vendor::all();
        return view('menus.create', compact('vendors'));
    }

    // Simpan menu baru
    public function store(Request $request)
    {
        $request->validate([
            'id_vendor' => 'required|exists:vendor,id_vendor',
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'path_gambar' => 'nullable|string|max:255',
        ]);

        Menu::create($request->all());

        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan');
    }

    // Edit menu
    public function edit($idmenu)
    {
        $menu = Menu::findOrFail($idmenu);
        $vendors = Vendor::all();
        return view('menus.edit', compact('menu', 'vendors'));
    }

    // Update menu
    public function update(Request $request, $idmenu)
    {
        $menu = Menu::findOrFail($idmenu);
        $menu->update($request->all());

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diupdate');
    }

    // Hapus menu
    public function destroy($idmenu)
    {
        $menu = Menu::findOrFail($idmenu);
        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus');
    }
}
