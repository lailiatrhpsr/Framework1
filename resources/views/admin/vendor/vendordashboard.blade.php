@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="display-6 fw-bold">Dashboard Vendor</h2>
            <p class="lead text-muted">{{ $vendor->nama_vendor }} | <span class="badge bg-light text-dark border">ID: {{ $vendor->id_vendor }}</span></p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="p-3 border rounded bg-white shadow-sm">
                <small class="text-muted d-block text-uppercase small fw-bold">Email Terdaftar</small>
                <span class="text-primary">{{ $vendor->email ?? '-' }}</span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h5 class="fw-bold">Produk & Menu</h5>
                    <p class="small text-muted">Atur daftar harga dan ketersediaan menu vendor Anda.</p>
                    <a href="{{ route('vendor.menus.index', $vendor->id_vendor) }}" class="btn btn-primary px-4">
                        Kelola Menu
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h5 class="fw-bold">Logistik Pesanan</h5>
                    <p class="small text-muted">Pantau semua pesanan masuk yang perlu diproses hari ini.</p>
                    <a href="{{ route('vendor.pesanan.index', $vendor->id_vendor) }}" class="btn btn-success px-4">
                        Lihat Semua Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Menu</h5>
                    <a href="{{ route('vendor.menus.create', $vendor->id_vendor) }}" class="btn btn-sm btn-outline-success">
                        + Tambah
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="small text-muted">
                                <th>NAMA MENU</th>
                                <th class="text-end">HARGA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                            <tr>
                                <td>{{ $menu->nama_menu }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($menu->harga,0,',','.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold">Pesanan Masuk</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr class="small">
                                <th>ID</th>
                                <th>CUSTOMER</th>
                                <th>STATUS</th>
                                <th>SUBTOTAL</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan as $p)
                            <tr>
                                <td class="fw-bold text-muted">#{{ $p->id_pesanan }}</td>
                                <td>{{ $p->nama }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $p->status == 'Diproses' ? 'bg-primary' : 'bg-warning text-dark' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($p->details->sum('subtotal'),0,',','.') }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('vendor.pesanan.show', [$vendor->id_vendor, $p->id_pesanan]) }}" 
                                       class="btn btn-sm btn-info text-white px-3">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection