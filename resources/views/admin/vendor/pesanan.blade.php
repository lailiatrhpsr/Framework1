@extends('layouts.app')

@section('content')
<h2>Pesanan Masuk</h2>
<table border="1" cellpadding="8">
    <tr>
        <th>ID Pesanan</th>
        <th>Customer</th>
        <th>Status</th>
        <th>Total</th>
    </tr>
    @foreach($pesanan as $p)
    <tr>
        <td>{{ $p->id_pesanan }}</td>
        <td>{{ $p->nama }}</td>
        <td>{{ $p->status }}</td>
        <td>{{ number_format($p->total, 0, ',', '.') }}</td>
    </tr>
    @endforeach
</table>
@endsection
