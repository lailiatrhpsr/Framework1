@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-format-list-bulleted"></i>
        </span> Kategori
    </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Kategori Baru</h4>
                <p class="card-description"> Masukkan nama kategori buku </p>
                
                <form class="forms-sample" action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="namaKategori">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" id="namaKategori" placeholder="Contoh: Novel, Komik, Biografi" required>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Simpan</button>
                    <button type="reset" class="btn btn-light">Batal</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Kategori</h4>
                <p class="card-description"> Total Kategori: <code>{{ $kategori->count() }}</code> </p>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> ID Kategori </th>
                                <th> Nama Kategori </th>
                                <th> Tanggal Dibuat </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategori as $index => $k)
                            <tr>
                                <td> {{ $index + 1 }} </td>
                                <td> KTG-{{ $k->idkategori }} </td>
                                <td> 
                                    <label class="badge badge-gradient-info">{{ $k->nama_kategori }}</label>
                                </td>
                                <td> {{ $k->created_at ? $k->created_at->format('d M Y') : '-' }} </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data kategori.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection