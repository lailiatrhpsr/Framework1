@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pesanan Lunas - Vendor {{ $id_vendor }}</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanan as $p)
            <tr>
                <td>{{ $p->id_pesanan }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->status }}</td>
                <td>Rp {{ number_format($p->total,0,',','.') }}</td>
                <td>
                    <a href="{{ route('vendor.pesanan.show', [$id_vendor, $p->id_pesanan]) }}" 
                       class="btn btn-sm btn-info">
                        Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada pesanan lunas</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
