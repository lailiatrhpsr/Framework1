@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Edit Barang</h3>
                <form id="editBarangForm" action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" value="{{ $barang->nama_barang }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control" value="{{ $barang->harga }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" value="{{ $barang->stok }}" required>
                    </div>
                    <button id="updateBtn" type="submit" class="btn btn-primary">
                        <span id="updateText">Update</span>
                    </button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById("editBarangForm").addEventListener("submit", function() {
    const btn = document.getElementById("updateBtn");
    const btnText = document.getElementById("updateText");

    btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Loading...';
    btn.disabled = true;
});
</script>
@endpush
