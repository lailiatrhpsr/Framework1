@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Menu untuk {{ $vendor->nama_vendor }}</h2>

    <!-- Form create menu -->
    <form action="{{ route('vendor.menus.store', $vendor->id_vendor) }}" 
          method="POST" enctype="multipart/form-data"> 
        @csrf

        <div class="mb-3">
            <label for="nama_menu" class="form-label">Nama Menu</label>
            <input type="text" name="nama_menu" id="nama_menu" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" name="harga" id="harga" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="path_gambar" class="form-label">Gambar Menu</label>
            <input type="file" name="path_gambar" id="path_gambar" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Simpan Menu</button>
        <a href="{{ route('vendor.menus.index', $vendor->id_vendor) }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
