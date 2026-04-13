<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPesanan;
use App\Models\Menu;

class DetailPesananController extends Controller
{
    // Tampilkan semua item dalam satu pesanan
    public function showByPesanan($idpesanan)
    {
        $details = DetailPesanan::with('menu')
            ->where('id_pesanan', $idpesanan)
            ->get();

        return view('detailpesanan.index', compact('details', 'id_pesanan'));
    }

    // Form tambah item ke pesanan
    public function create($idpesanan)
    {
        $menus = Menu::all();
        return view('detailpesanan.create', compact('menus', 'id_pesanan'));
    }

    // Simpan item baru
    public function store(Request $request)
    {
        $request->validate([
            'id_menu' => 'required|exists:menu,id_menu',
            'id_pesanan' => 'required|exists:pesanan,id_pesanan',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:255',
        ]);

        $menu = Menu::findOrFail($request->idmenu);

        DetailPesanan::create([
            'id_menu' => $menu->id_menu,
            'id_pesanan' => $request->id_pesanan,
            'jumlah' => $request->jumlah,
            'harga' => $menu->harga,
            'subtotal' => $menu->harga * $request->jumlah,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('detailpesanan.showByPesanan', $request->idpesanan)
                         ->with('success', 'Item berhasil ditambahkan ke pesanan');
    }

    // Form edit item
    public function edit($iddetail_pesanan)
    {
        $detail = DetailPesanan::findOrFail($iddetail_pesanan);
        return view('detailpesanan.edit', compact('detail'));
    }

    // Update item pesanan
    public function update(Request $request, $iddetail_pesanan)
    {
        $detail = DetailPesanan::findOrFail($iddetail_pesanan);

        $detail->update($request->only(['jumlah', 'catatan']));
        $detail->subtotal = $detail->harga * $detail->jumlah;
        $detail->save();

        return redirect()->route('detailpesanan.showByPesanan', $detail->idpesanan)
                         ->with('success', 'Item pesanan berhasil diupdate');
    }

    // Hapus item pesanan
    public function destroy($iddetail_pesanan)
    {
        $detail = DetailPesanan::findOrFail($iddetail_pesanan);
        $idpesanan = $detail->idpesanan;
        $detail->delete();

        return redirect()->route('detailpesanan.showByPesanan', $idpesanan)
                         ->with('success', 'Item pesanan berhasil dihapus');
    }
}
