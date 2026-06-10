@extends('layouts.customer')

@section('content')
<div class="container">
    <h2 class="mb-4">Pesanan Saya</h2>

    @if($pesananTerbaru)
        <div class="card bg-transparent border-0 mb-4">
            <div class="card-body">
                <h5 class="fw-bold">ID Pesanan: #{{ $pesananTerbaru->id_pesanan }}</h5>
                <p>Nama Customer: {{ $pesananTerbaru->nama }}</p>
                <p>Status: {{ $pesananTerbaru->status }}</p>
                <p>Total: Rp {{ number_format($pesananTerbaru->total,0,',','.') }}</p>
                <a href="{{ route('customer.pesanan.show', $pesananTerbaru->id_pesanan) }}" class="btn btn-primary" style="background-color: #a855f7">
                    Lihat Detail
                </a>
            </div>
        </div>
    @else
        <p class="text-muted">Belum ada pesanan.</p>
    @endif
</div>
@endsection
