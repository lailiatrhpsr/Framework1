@extends('layouts.customer')

@section('title', 'Jelajahi Menu')

@section('content')
<div class="container">
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <h1 class="fw-extrabold display-6 mb-2" style="font-weight: 800;">Jelajahi Menu Lezat</h1>
            <p class="text-secondary fs-5">Temukan rasa terbaik dari vendor-vendor pilihan kami.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="d-inline-flex align-items-center p-2 bg-white rounded-pill shadow-sm border px-3">
                <i class="bi bi-grid-fill text-purple me-2"></i>
                <span class="fw-bold">{{ $menus->count() }} Menu Tersedia</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @foreach($menus as $menu)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 border-0 shadow-sm overflow-hidden">
                <div class="position-relative">
                    <img src="{{ $menu->path_gambar ? asset('storage/'.$menu->path_gambar) : asset('images/no-image.png') }}" 
                         class="card-img-top" 
                         alt="{{ $menu->nama_menu }}"
                         style="height: 200px; object-fit: cover;">
                    
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-white text-dark shadow-sm px-3 py-2 rounded-pill fw-bold" style="color: #a855f7 !important;">
                            Rp {{ number_format($menu->harga,0,',','.') }}
                        </span>
                    </div>
                </div>

                <div class="card-body d-flex flex-column p-4">
                    <div class="mb-3">
                        <h5 class="fw-bold mb-1 text-truncate">{{ $menu->nama_menu }}</h5>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="bi bi-shop me-1 text-purple"></i>
                            <span>{{ $menu->vendor->nama_vendor ?? 'Warung Sederhana' }}</span>
                        </div>
                    </div>

                    <form action="{{ route('customer.cart.add') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="id_menu" value="{{ $menu->id_menu }}">
                        
                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <label class="small text-uppercase fw-bold text-muted mb-1" style="font-size: 0.65rem;">Qty</label>
                                <input type="number" name="jumlah" value="1" min="1" class="form-control form-control-sm border-0 bg-light text-center fw-bold rounded-3">
                            </div>
                            <div class="col-8">
                                <label class="small text-uppercase fw-bold text-muted mb-1" style="font-size: 0.65rem;">Catatan</label>
                                <input type="text" name="catatan" class="form-control form-control-sm border-0 bg-light rounded-3" placeholder="Pedas?">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-purple w-100 rounded-3 py-2 shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    .text-purple { color: #a855f7; }
    .fw-extrabold { font-weight: 800; }
</style>
@endsection