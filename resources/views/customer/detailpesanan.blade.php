@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Pesanan #{{ $pesanan->id_pesanan }}</h2>
    <p><strong>Nama:</strong> {{ $pesanan->nama }}</p>
    <p><strong>Status:</strong> 
        <span class="badge {{ in_array($pesanan->status, ['success', 'settlement']) ? 'bg-success' : 'bg-warning' }}">
            {{ strtoupper($pesanan->status) }}
        </span>
    </p>
    <p><strong>Total:</strong> Rp {{ number_format($pesanan->total,0,',','.') }}</p>

    @if(isset($dataUri) && $dataUri)
        <div class="card my-4 border-success" style="max-width: 300px;">
            <div class="card-header bg-success text-white text-center fw-bold">
                QR Code Bukti Pembayaran
            </div>
            <div class="card-body text-center bg-light">
                <p class="text-muted small mb-2">Scan QR Code di bawah ini untuk validasi transaksi[cite: 20].</p>
                <img src="{{ $dataUri }}" alt="QR Code Pesanan" class="img-fluid border bg-white p-2 mb-2" style="max-width: 180px;">
                <div class="font-monospace small fw-bold text-secondary">{{ $pesanan->id_pesanan }}</div>
            </div>
        </div>
    @endif
    
    <h3>Item Pesanan</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Vendor</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesanan->details as $detail)
            <tr>
                <td>{{ $detail->menu->nama_menu }}</td>
                <td>{{ $detail->vendor->nama_vendor }}</td>
                <td>{{ $detail->jumlah }}</td>
                <td>Rp {{ number_format($detail->harga,0,',','.') }}</td>
                <td>Rp {{ number_format($detail->subtotal,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection