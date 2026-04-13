@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Pesanan #{{ $pesanan->id_pesanan }}</h2>
    <p><strong>Nama:</strong> {{ $pesanan->nama }}</p>
    <p><strong>Status:</strong> {{ $pesanan->status }}</p>
    <p><strong>Total:</strong> Rp {{ number_format($pesanan->total,0,',','.') }}</p>

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
