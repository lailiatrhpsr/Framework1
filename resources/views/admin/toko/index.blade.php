@extends('layouts.app')

@section('title', 'Daftar Lokasi Toko')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>📋 List Toko Terdaftar</h3>
        <a href="{{ route('toko.create') }}" class="btn btn-primary">+ Tambah Toko Baru</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Barcode / Kode</th>
                        <th>Nama Toko</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Akurasi Awal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allToko as $toko)
                    <tr>
                        <td><code>{{ $toko->barcode }}</code></td>
                        <td><strong>{{ $toko->nama_toko }}</strong></td>
                        <td>{{ $toko->latitude }}</td>
                        <td>{{ $toko->longitude }}</td>
                        <td>{{ $toko->accuracy }} meter</td>
                        <td class="text-center">
                            <a href="{{ route('toko.cetakQr', $toko->barcode) }}" class="btn btn-sm btn-warning">
                                🖨️ Cetak Barcode (QR)
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data toko yang didaftarkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection