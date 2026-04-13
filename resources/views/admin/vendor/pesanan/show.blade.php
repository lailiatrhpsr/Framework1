@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Pesanan #{{ $pesanan->id_pesanan }}</h2>
    <p><strong>Customer:</strong> {{ $pesanan->nama }}</p>
    <p><strong>Status:</strong> {{ $pesanan->status }}</p>

    <h3>Item Pesanan Vendor</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesanan->details as $detail)
            <tr>
                <td>{{ $detail->menu->nama_menu }}</td>
                <td>{{ $detail->jumlah }}</td>
                <td>Rp {{ number_format($detail->harga,0,',','.') }}</td>
                <td>Rp {{ number_format($detail->subtotal,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
