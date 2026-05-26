@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Data Customer</h2>
        <div>
            <a href="{{ route('customer.create.blob') }}" class="btn btn-primary btn-sm">Tambah (BLOB)</a>
            <a href="{{ route('customer.create.path') }}" class="btn btn-success btn-sm">Tambah (File Path)</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat lengkap</th>
                <th>Foto Hasil Kamera</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $index => $c)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $c->nama }}</td>
                <td>{{ $c->alamat }}, {{ $c->kecamatan }}, {{ $c->kota }}, {{ $c->provinsi }} ({{ $c->kodepos }})</td>
                <td class="text-center">
                    @if($c->foto)
                        @php
                            // 1. Baca isi data foto, baik yang berupa biner BLOB maupun teks string path
                            $isiFoto = is_resource($c->foto) ? stream_get_contents($c->foto) : $c->foto;
                        @endphp

                        @if(str_starts_with($isiFoto, 'storage/'))
                            <img src="{{ asset($isiFoto) }}" alt="Foto Path" style="max-width: 120px;" class="border rounded shadow-sm p-1 bg-white">
                        @else
                            <img src="data:image/png;base64,{{ base64_encode($isiFoto) }}" alt="Foto BLOB" style="max-width: 120px;" class="border rounded shadow-sm p-1 bg-white">
                        @endif
                    @else
                        <span class="text-muted small">Tidak ada foto</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection