@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Menu - {{ $vendor->nama_vendor }}</h2>

    <!-- Tombol aksi -->
    <div class="mb-3">
        <a href="{{ route('vendor.vendordashboard', $vendor->id_vendor) }}" class="btn btn-secondary">
            Kembali ke Dashboard
        </a>
        <a href="{{ route('vendor.menus.create', $vendor->id_vendor) }}" class="btn btn-success">
            Tambah Menu
        </a>
    </div>

    <!-- Tabel menu -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nama Menu</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menus as $menu)
            <tr>
                <td>{{ $menu->nama_menu }}</td>
                <td>Rp {{ number_format($menu->harga,0,',','.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center">Belum ada menu</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
