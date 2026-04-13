<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Vendor;

class PesananController extends Controller
{
    // Tambah item ke keranjang
    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $menu = Menu::findOrFail($request->id_menu);

        $cart[] = [
            'id_menu' => $request->id_menu,
            'id_vendor' => $menu->id_vendor,
            'jumlah'  => $request->jumlah,
            'catatan' => $request->catatan,
        ];

        session()->put('cart', $cart);

        return redirect()->route('customer.menus')->with('success', 'Menu ditambahkan ke keranjang');
    }

    // Tampilkan isi keranjang
    public function cart()
    {
        $cart = session()->get('cart', []);
        $menus = Menu::whereIn('id_menu', collect($cart)->pluck('id_menu'))->get();

        return view('customer.cart', compact('cart', 'menus'));
    }

    // Update jumlah item di keranjang
    public function updateCart(Request $request, $id_menu)
    {
        $cart = session()->get('cart', []);

        foreach ($cart as &$item) {
            if ($item['id_menu'] == $id_menu) {
                $item['jumlah'] = $request->jumlah;
                $item['catatan'] = $request->catatan ?? $item['catatan'];
                break;
            }
        }

        session()->put('cart', $cart);

        return redirect()->route('customer.cart')->with('success', 'Keranjang diperbarui');
    }

    // Hapus item dari keranjang
    public function deleteFromCart($id_menu)
    {
        $cart = session()->get('cart', []);

        $cart = array_filter($cart, function($item) use ($id_menu) {
            return $item['id_menu'] != $id_menu;
        });

        session()->put('cart', $cart);

        return redirect()->route('customer.cart')->with('success', 'Item dihapus dari keranjang');
    }

    public function showCheckout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.menus')->with('error', 'Keranjang kosong');
        }
        
        // Ambil data menu berdasarkan ID yang ada di session
        $menuIds = collect($cart)->pluck('id_menu')->toArray();
        $menus = Menu::whereIn('id_menu', $menuIds)->get();

        return view('customer.checkout', compact('cart', 'menus'));
    }
    // Proses checkout keranjang
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.menus')->with('error', 'Keranjang kosong');
        }

        // Buat pesanan
        $pesanan = Pesanan::create([
            'nama'         => $request->nama,
            'total'        => collect($cart)->sum(function($item){
                                $menu = Menu::find($item['id_menu']);
                                return $menu->harga * $item['jumlah'];
                             }),
            'metode_bayar' => $request->metode_bayar,
            'status'       => 'pending',
        ]);

        // Simpan detail pesanan
        foreach ($cart as $item) {
            $menu = Menu::find($item['id_menu']);
            DetailPesanan::create([
                'id_menu'    => $menu->id_menu,
                'id_vendor'  => $menu->id_vendor,
                'id_pesanan' => $pesanan->id_pesanan,
                'jumlah'     => $item['jumlah'],
                'harga'      => (int)$menu->harga,
                'subtotal'   => (int)($menu->harga * $item['jumlah']),
                'catatan'    => $item['catatan'] ?? null,
            ]);
        }

        // Integrasi Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $pesanan->id_pesanan,
                'gross_amount' => $pesanan->total,
            ],
            'customer_details' => [
                'first_name' => $pesanan->nama,
                'email'      => $request->email ?? 'guest@example.com',
            ],
        ];

        $response = Http::withBasicAuth(env('MIDTRANS_SERVER_KEY'), '')
            ->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

        $snapToken = $response['token'];

        // Kosongkan keranjang setelah checkout
        session()->forget('cart');

        return view('customer.payment', compact('pesanan', 'snapToken'));
    }

    // Notification handler dari Midtrans (webhook)
    public function notificationHandler(Request $request)
    {
        $orderId = $request->order_id;
        $transactionStatus = $request->transaction_status;

        $pesanan = Pesanan::find($orderId);
        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        if ($transactionStatus == 'settlement') {
            $pesanan->status = 'paid';
        } elseif ($transactionStatus == 'pending') {
            $pesanan->status = 'pending';
        } elseif (in_array($transactionStatus, ['cancel','expire','deny'])) {
            $pesanan->status = 'cancelled';
        }
        $pesanan->save();

        return response()->json(['message' => 'Status pesanan diperbarui']);
    }

    // Tampilkan detail pesanan
    public function show($id_pesanan)
    {
        $pesanan = Pesanan::with('details.menu')->findOrFail($id_pesanan);
        return view('customer.detailpesanan', compact('pesanan'));
    }
}
