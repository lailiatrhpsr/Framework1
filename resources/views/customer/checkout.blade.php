@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-7">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('customer.cart') }}" class="btn btn-sm btn-light rounded-circle me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h2 class="fw-bold mb-0">Informasi Pengiriman</h2>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <form action="{{ route('customer.checkout') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="nama" class="form-label small fw-bold text-muted text-uppercase">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-person text-purple"></i></span>
                            <input type="text" name="nama" class="form-control border-0 bg-light py-2" placeholder="Masukkan nama penerima" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label small fw-bold text-muted text-uppercase">Email (Opsional)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-purple"></i></span>
                            <input type="email" name="email" class="form-control border-0 bg-light py-2" placeholder="nama@email.com">
                        </div>
                        <div class="form-text mt-2 small">E-ticket/struk akan dikirimkan ke email ini.</div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label small fw-bold text-muted text-uppercase">Pilih Metode Pembayaran</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="metode_bayar" id="tf_bank" value="Transfer Bank" checked required>
                                <label class="btn btn-outline-purple w-100 p-3 text-start" for="tf_bank">
                                    <i class="bi bi-bank fs-4 d-block mb-2"></i>
                                    <span class="fw-bold d-block">Transfer Bank</span>
                                    <small class="text-muted">VA BCA, Mandiri, BNI, dll</small>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="metode_bayar" id="qris" value="QRIS" required>
                                <label class="btn btn-outline-purple w-100 p-3 text-start" for="qris">
                                    <i class="bi bi-qr-code-scan fs-4 d-block mb-2"></i>
                                    <span class="fw-bold d-block">QRIS / E-Wallet</span>
                                    <small class="text-muted">Gopay, OVO, ShopeePay</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-purple w-100 rounded-pill py-3 fw-bold shadow-sm">
                        Bayar Sekarang <i class="bi bi-lock-fill ms-2"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <h4 class="fw-bold mb-4">Ringkasan Pesanan</h4>
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @php $total = 0; @endphp
                        @foreach($cart as $item)
                            @php
                                $menu = $menus->firstWhere('id_menu', $item['id_menu']);
                                $subtotal = $menu->harga * $item['jumlah'];
                                $total += $subtotal;
                            @endphp
                            <li class="list-group-item border-0 px-4 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $menu->nama_menu }}</h6>
                                        <small class="text-muted">{{ $item['jumlah'] }}x @ Rp {{ number_format($menu->harga,0,',','.') }}</small>
                                    </div>
                                    <span class="fw-bold text-purple">Rp {{ number_format($subtotal,0,',','.') }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer bg-white border-0 px-4 py-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-muted">Biaya Layanan</span>
                        <span class="text-success fw-bold">Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fs-5 fw-bold">Total Pembayaran</span>
                        <span class="fs-5 fw-bold text-purple">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-white rounded-4 border border-light shadow-sm d-flex align-items-center">
                <i class="bi bi-info-circle text-purple fs-4 me-3"></i>
                <small class="text-muted">Pesanan Anda akan langsung diproses setelah pembayaran diverifikasi secara otomatis.</small>
            </div>
        </div>
    </div>
</div>

<style>
    .text-purple { color: #a855f7 !important; }
    .border-purple { border: 2px solid #a855f7 !important; }
    .btn-purple { background-color: #a855f7; color: white; border: none; }
    .btn-purple:hover { background-color: #9333ea; color: white; }
    .input-group-text { border-radius: 0.5rem 0 0 0.5rem; }
    .form-control { border-radius: 0 0.5rem 0.5rem 0; }
</style>
@endsection