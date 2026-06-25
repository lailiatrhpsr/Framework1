@extends('layouts.app')

@section('title', 'Tambah Toko Baru')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">➕ Form Pendaftaran Toko Baru</h5>
                </div>
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('toko.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="barcode" class="form-label">Kode / Barcode Unik Toko</label>
                            <input type="text" class="form-label form-control" id="barcode" name="barcode" placeholder="Contoh: TK-001 atau STR-ABC" value="{{ old('barcode') }}" required>
                            <div class="form-text">Kode ini akan diubah menjadi QR Code untuk di-scan sales.</div>
                        </div>

                        <div class="mb-3">
                            <label for="nama_toko" class="form-label">Nama Toko</label>
                            <input type="text" class="form-control" id="nama_toko" name="nama_toko" placeholder="Contoh: Toko Jaya Abadi" value="{{ old('nama_toko') }}" required>
                        </div>

                        <div class="p-3 bg-light border rounded mb-3">
                            <h6 class="mb-2">📍 Koordinat Geolocation Toko</h6>
                            <p class="text-muted small">Anda bisa mengisi manual atau berdiri di lokasi toko lalu tekan tombol di bawah ini:</p>
                            
                            <button type="button" id="get-toko-location" class="btn btn-sm btn-secondary mb-3">
                                🎯 Dapatkan Lokasi Saya Sekarang
                            </button>
                            <div id="gps-status" class="text-info small mb-2" style="display:none;"></div>

                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label for="latitude" class="form-label small">Latitude</label>
                                    <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude') }}" required placeholder="-7.xxxxxx">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="longitude" class="form-label small">Longitude</label>
                                    <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude') }}" required placeholder="112.xxxxxx">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="accuracy" class="form-label small">Akurasi GPS Alat (Meter)</label>
                                    <input type="text" class="form-control" id="accuracy" name="accuracy" value="{{ old('accuracy') }}" required placeholder="Misal: 15.5">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('toko.index') }}" class="btn btn-light">Kembali</a>
                            <button type="submit" class="btn btn-success">Simpan Data Toko</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript pembantu untuk mengambil lokasi Owner saat mendaftarkan toko secara langsung di tempat
document.getElementById('get-toko-location').addEventListener('click', () => {
    const statusDiv = document.getElementById('gps-status');
    statusDiv.style.display = "block";
    statusDiv.innerText = "Sedang mengunci satelit GPS...";

    if (!navigator.geolocation) {
        statusDiv.innerText = "Browser Anda tidak mendukung Geolocation.";
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (position) => {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
            document.getElementById('accuracy').value = position.coords.accuracy.toFixed(2);
            statusDiv.className = "text-success small mb-2";
            statusDiv.innerText = "✓ Berhasil mengambil koordinat lokasi saat ini!";
        },
        (error) => {
            statusDiv.className = "text-danger small mb-2";
            statusDiv.innerText = "Gagal mengambil lokasi: " + error.message;
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
});
</script>
@endsection