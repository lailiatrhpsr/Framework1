@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-open-page-variant"></i>
        </span> Buku
    </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Koleksi Buku</h4>
                <p class="card-description"> Masukkan detail buku baru </p>
                
                <form class="forms-sample" action="{{ route('buku.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label>Pilih Kategori</label>
                        <select class="form-control" name="idkategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $k)
                                <option value="{{ $k->idkategori }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kode Buku</label>
                        <input type="text" name="kode" class="form-control" placeholder="Contoh: NV-01" required>
                    </div>

                    <div class="form-group">
                        <label>Judul Buku</label>
                        <input type="text" name="judul" class="form-control" placeholder="Judul Lengkap" required>
                    </div>

                    <div class="form-group">
                        <label>Pengarang</label>
                        <input type="text" name="pengarang" class="form-control" placeholder="Nama Pengarang" required>
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
                <h4 class="card-title">Daftar Buku</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th> Kategori </th>
                                <th> Kode </th>
                                <th> Judul </th>
                                <th> Pengarang </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($buku as $b)
                            <tr>
                                <td>
                                    <label class="badge badge-gradient-success">
                                        {{ $b->kategori->nama_kategori ?? 'Tidak Ada Kategori' }}
                                    </label>
                                </td>
                                <td> {{ $b->kode }} </td>
                                <td> {{ $b->judul }} </td>
                                <td> {{ $b->pengarang }} </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data buku.</td>
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