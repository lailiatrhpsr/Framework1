@extends('layouts.app')

@section('content')
<h2>Detail Pesanan #{{ $pesanan->id_pesanan }}</h2>
<p>Nama Customer: {{ $pesanan->nama }}</p>
<p>Total: {{ number_format($pesanan->total, 0, ',', '.') }}</p>
<p>Metode Bayar: {{ $pesanan->metode_bayar }}</p>
<p>Status: {{ $pesanan->status }}</p>

<h3>Detail Item</h3>
<table border="1" cellpadding="8">
    <tr>
        <th>Menu</th>
        <th>Jumlah</th>
        <th>Harga</th>
        <th>Subtotal</th>
        <th>Catatan</th>
    </tr>
    @foreach($pesanan->details as $detail)
    <tr>
        <td>{{ $detail->menu->nama }}</td>
        <td>{{ $detail->jumlah }}</td>
        <td>{{ number_format($detail->harga, 0, ',', '.') }}</td>
        <td>{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
        <td>{{ $detail->catatan }}</td>
    </tr>
    @endforeach
</table>

<h3>Update Status Pesanan</h3>
<form action="{{ route('vendor.pesanan.updateStatus', $pesanan->id_pesanan) }}" method="POST">
    @csrf
    <select name="status">
        <option value="dikirim">Pesanan Dikirim</option>
        <option value="selesai">Pesanan Selesai</option>
    </select>
    <button type="submit">Update</button>
</form>
@endsection
