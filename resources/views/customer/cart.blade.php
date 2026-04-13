@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-cart3 fs-2 text-purple me-3"></i>
        <h2 class="fw-bold mb-0">Keranjang Pesanan</h2>
    </div>

    @if(empty($cart))
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <div class="mb-3">
                <i class="bi bi-bag-x text-muted" style="font-size: 4rem;"></i>
            </div>
            <h4 class="text-secondary">Keranjang kamu masih kosong</h4>
            <p class="text-muted">Yuk, cari makanan lezat dan isi keranjangmu!</p>
            <a href="{{ route('customer.menus') }}" class="btn btn-purple rounded-pill px-4 py-2 mt-2">
                Lihat Menu Sekarang
            </a>
        </div>
    @else
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-uppercase fw-bold text-muted">
                                <th class="ps-4 py-3">Menu</th>
                                <th class="py-3 text-center">Jumlah</th>
                                <th class="py-3 text-end">Harga</th>
                                <th class="py-3 text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $item)
                                @php
                                    $menu = $menus->firstWhere('id_menu', $item['id_menu']);
                                    $subtotal = $menu->harga * $item['jumlah'];
                                @endphp
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $menu->path_gambar ? asset('storage/'.$menu->path_gambar) : asset('images/no-image.png') }}" 
                                                 class="rounded-3 me-3" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <h6 class="fw-bold mb-0">{{ $menu->nama_menu }}</h6>
                                                <small class="text-muted"><i class="bi bi-shop me-1"></i>{{ $menu->vendor->nama_vendor }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <form action="{{ route('customer.cart.update', $item['id_menu']) }}" method="POST" class="d-flex align-items-center bg-light rounded-pill px-2">
                                                @csrf
                                                <input type="hidden" name="id_menu" value="{{ $item['id_menu'] }}">
                                                
                                                <button name="action" value="decrease" class="btn btn-sm text-purple border-0 px-2">
                                                    <i class="bi bi-dash-circle-fill"></i>
                                                </button>
                                                
                                                <span class="fw-bold px-2" style="min-width: 30px; text-align: center;">
                                                    {{ $item['jumlah'] }}
                                                </span>
                                                
                                                <button name="action" value="increase" class="btn btn-sm text-purple border-0 px-2">
                                                    <i class="bi bi-plus-circle-fill"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('customer.cart.delete', $item['id_menu']) }}" method="POST" class="ms-2">
                                                @csrf
                                                <input type="hidden" name="id_menu" value="{{ $item['id_menu'] }}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="return confirm('Hapus menu ini?')">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="py-3 text-end text-muted small">
                                        Rp {{ number_format($menu->harga,0,',','.') }}
                                    </td>
                                    <td class="py-3 text-end pe-4 fw-bold text-purple">
                                        Rp {{ number_format($subtotal,0,',','.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Harga ({{ count($cart) }} Menu)</span>
                    <span class="fw-semibold">Rp {{ number_format(collect($cart)->sum(fn($i) => $menus->firstWhere('id_menu', $i['id_menu'])->harga * $i['jumlah']), 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Biaya Layanan</span>
                    <span class="fw-semibold text-success">Gratis</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold fs-5">Total Bayar</span>
                    <span class="fw-bold fs-5 text-purple">Rp {{ number_format(collect($cart)->sum(fn($i) => $menus->firstWhere('id_menu', $i['id_menu'])->harga * $i['jumlah']), 0, ',', '.') }}</span>
                </div>

                <a href="{{ route('customer.checkout') }}" class="btn btn-purple w-100 rounded-pill py-3 fw-bold shadow-sm">
                    Lanjut ke Checkout <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .text-purple { color: #a855f7 !important; }
    .btn-purple { background-color: #a855f7; color: white; border: none; }
    .btn-purple:hover { background-color: #9333ea; color: white; }
</style>
@endsection