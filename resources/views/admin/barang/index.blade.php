@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-open-page-variant"></i>
        </span> Barang
    </h3>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Barang</h4>
                <form id="barangForm" action="{{ route('barang.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>
                    <button id="submitBtn" type="submit" class="btn btn-primary mb-3">
                        <span id="btnText">Simpan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Barang</h4>
                <div class="table-responsive">
                    <table id="barangTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang as $b)
                            <tr>
                                <td>{{ $b->kode_barang }}</td>
                                <td>{{ $b->nama_barang }}</td>
                                <td>{{ $b->harga }}</td>
                                <td>{{ $b->stok }}</td>
                                <td>
                                    <a href="{{ route('barang.edit', $b->id_barang) }}" 
                                    class="btn btn-warning btn-sm editBtn">Edit</a>

                                    <form action="{{ route('barang.destroy', $b->id_barang) }}" method="POST" style="display:inline;" class="deleteForm">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm deleteBtn">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('barang.label') }}" class="btn btn-primary">Cetak Label</a>
                </div>
            </div>
        </div>
     </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById("barangForm").addEventListener("submit", function() {
    const btn = document.getElementById("submitBtn");
    const btnText = document.getElementById("btnText");
    btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Loading...';
    btn.disabled = true;
});

document.querySelectorAll(".editBtn").forEach(function(btn) {
    btn.addEventListener("click", function() {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Loading...';
        btn.classList.remove("btn-warning");
        btn.classList.add("btn-secondary");
    });
});

document.querySelectorAll(".deleteForm").forEach(function(form) {
    form.addEventListener("submit", function(e) {
        const btn = form.querySelector(".deleteBtn");
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Loading...';
        btn.disabled = true;
    });
});
</script>
@endpush
