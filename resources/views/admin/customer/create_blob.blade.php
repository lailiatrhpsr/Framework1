@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Customer 1 (Penyimpanan Gambar BLOB)</h2>
    <hr>
    
    <form action="{{ route('customer.store.blob') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="fw-bold">Nama Customer</label>
                    <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama...">
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2" placeholder="Nama jalan..."></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Provinsi</label><input type="text" name="provinsi" class="form-control"></div>
                    <div class="col-md-6 mb-3"><label>Kota/Kabupaten</label><input type="text" name="kota" class="form-control"></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Kecamatan</label><input type="text" name="kecamatan" class="form-control"></div>
                    <div class="col-md-6 mb-3"><label>Kode Pos</label><input type="text" name="kodepos" class="form-control"></div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">Simpan Data Customer (BLOB)</button>
            </div>

            <div class="col-md-6 text-center">
                <label class="fw-bold d-block mb-2">Akses Kamera Laptop</label>
                
                <video id="webcam" autoplay playsinline class="border bg-dark w-100 rounded mb-2" style="max-height: 240px; transform: scaleX(-1);"></video>
                
                <button type="button" class="btn btn-secondary btn-sm mb-3" onclick="takeSnapshot()">📷 Ambil Jepretan Foto</button>
                
                <label class="fw-bold d-block mb-2">Hasil Preview Snapshot</label>
                <canvas id="canvas" style="display:none;"></canvas>
                
                <img id="preview_snapshot" src="https://via.placeholder.com/320x240?text=Belum+Ada+Foto" class="img-fluid border rounded bg-white shadow-sm" style="max-height: 180px;">
                
                <input type="hidden" name="image_camera" id="image_camera">
            </div>
        </div>
    </form>
</div>

<script>
    const video = document.getElementById('webcam');
    const canvas = document.getElementById('canvas');
    const preview = document.getElementById('preview_snapshot');
    const inputCamera = document.getElementById('image_camera');

    // 1. Izin browser dan nyalakan kamera laptop otomatis saat halaman terbuka
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(stream) {
            video.srcObject = stream;
        })
        .catch(function(error) {
            alert("Gagal mengakses kamera laptop: " + error);
        });
    }

    // 2. Fungsi Mengambil Gambar Snapshot dari Aliran Video ke Canvas
    function takeSnapshot() {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        // Mirror gambar agar sesuai dengan tampilan webcam yang sudah di-flip horizontal
        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Ambil data gambar dari canvas diubah menjadi string Base64 format PNG
        const base64Image = canvas.toDataURL('image/png');

        preview.src = base64Image;
        // Simpan string Base64 ke input hidden agar bisa dikirim ke server saat form submit
        inputCamera.value = base64Image;
    }
</script>
@endsection