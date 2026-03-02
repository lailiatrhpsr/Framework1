@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pilih Barang untuk Cetak Label</h2>

    <form action="{{ route('barang.cetak') }}" method="POST">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pilih</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barang as $b)
                <tr>
                    <td><input type="checkbox" name="barang[]" value="{{ $b->id_barang }}"></td>
                    <td>{{ $b->kode_barang }}</td>
                    <td>{{ $b->nama_barang }}</td>
                    <td>{{ $b->harga }}</td>
                    <td>{{ $b->stok }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mb-3">
            <label>X (1-5):</label>
            <input type="number" name="x" min="1" max="5" required>
        </div>
        <div class="mb-3">
            <label>Y (1-8):</label>
            <input type="number" name="y" min="1" max="8" required>
        </div>

        <button type="submit" class="btn btn-success">Cetak PDF</button>
    </form>
</div>
@endsection
