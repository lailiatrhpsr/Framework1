@extends('layouts.app')

@section('title', 'Barcode Scanner')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h5 class="mb-0 fw-bold">📷 Scan Barcode Barang</h5>
                </div>
                <div class="card-body bg-light p-4">
                    <div id="reader" class="bg-white rounded-3 border overflow-hidden" style="width: 100%; min-height: 250px;"></div>
                    <div class="text-center mt-3">
                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill small">
                            Posisikan barcode berada di dalam kotak scanner
                        </span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold small mb-3 text-center tracking-wide">
                        Hasil Pemindaian
                    </h6>
                    
                    <div id="barang-info">
                        <div class="text-center py-4 text-muted">
                            <div class="spinner-grow spinner-grow-sm text-secondary mb-2" role="status"></div>
                            <p class="mb-0 small">Menunggu barcode dipindai...</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<audio id="beep" src="/sounds/beep.mp3"></audio>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrcodeScanner;

    function startScanner() {
        html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    function resetScanner() {
        document.getElementById('barang-info').innerHTML = `
            <div class="text-center py-4 text-muted">
                <div class="spinner-grow spinner-grow-sm text-secondary mb-2" role="status"></div>
                <p class="mb-0 small">Menunggu barcode dipindai...</p>
            </div>`;
        startScanner();
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Bunyi beep
        document.getElementById('beep').play().catch(err => console.log(err));

        // Stop scanner
        html5QrcodeScanner.clear();

        // Tampilkan loading placeholder sesaat sebelum fetch selesai
        document.getElementById('barang-info').innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                <p class="mt-2 small text-muted">Mengambil data barang...</p>
            </div>`;

        // Ambil data barang via AJAX
        fetch(`/barang/get/${decodedText}`)
            .then(response => response.json())
            .then(data => {
                // Sesuai dengan tampilan gambar: List bersih, teks bold gelap, tombol hitam melengkung
                let html = `
                    <div class="d-flex flex-column gap-3 mb-4 text-dark" style="font-family: sans-serif;">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span class="text-muted">ID Barang</span>
                            <span class="fw-semibold text-secondary fs-5">#${data.id_barang}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span class="text-muted">Nama Barang</span>
                            <span class="fw-bold fs-5" style="color: #2c3e50;">${data.nama_barang}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2">
                            <span class="text-muted">Harga</span>
                            <span class="fw-bold fs-3" style="color: #2c3e50;">Rp ${Number(data.harga).toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                    <div class="d-grid mt-2">
                        <button onclick="resetScanner()" class="btn btn-dark text-white fw-bold py-3 rounded-3 shadow-sm border-0" style="background-color: #1a1a1a; letter-spacing: 0.5px;">
                            Scan Barang Lagi
                        </button>
                    </div>`;
                
                document.getElementById('barang-info').innerHTML = html;
            })
            .catch(err => {
                document.getElementById('barang-info').innerHTML = `
                    <div class="alert alert-danger border-0 rounded-3 text-center mb-0 p-3" role="alert">
                        <p class="mb-0 fw-semibold text-danger">⚠️ Barang tidak ditemukan!</p>
                        <div class="d-grid mt-3">
                            <button onclick="resetScanner()" class="btn btn-sm btn-danger rounded-pill">Coba Lagi</button>
                        </div>
                    </div>`;
            });
    }

    function onScanFailure(error) {
        console.warn(`Scan gagal: ${error}`);
    }

    // Jalankan scanner pertama kali
    document.addEventListener("DOMContentLoaded", function() {
        startScanner();
    });
</script>
@endpush