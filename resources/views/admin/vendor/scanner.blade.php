@extends('layouts.app')

@section('title', 'Vendor Scanner')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h5 class="mb-0 fw-bold">📷 Scan QR Code Customer</h5>
                </div>
                <div class="card-body bg-light p-4">
                    <div id="reader" class="bg-white rounded-3 border overflow-hidden" style="width: 100%; min-height: 250px;"></div>
                    <div class="text-center mt-3">
                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill small">
                            Posisikan QR Code di dalam area kotak kamera
                        </span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted fw-bold small mb-3 text-center tracking-wide">
                        Detail Pesanan Customer
                    </h6>
                    
                    <div id="pesanan-info">
                        <div class="text-center py-4 text-muted">
                            <div class="spinner-grow spinner-grow-sm text-secondary mb-2" role="status"></div>
                            <p class="mb-0 small">Menunggu QR Code dipindai...</p>
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
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('beep').play().catch(err => console.log(err));
        html5QrcodeScanner.clear();

        document.getElementById('pesanan-info').innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                <p class="mt-2 small text-muted">Mengambil data pesanan...</p>
            </div>`;

        fetch(`/vendor/pesanan/${decodedText}`)
            .then(res => res.json())
            .then(data => {
               
                let statusBadge = 'bg-secondary';
                if(data.status.toLowerCase() === 'selesai' || data.status.toLowerCase() === 'paid') statusBadge = 'bg-success';
                if(data.status.toLowerCase() === 'proses' || data.status.toLowerCase() === 'pending') statusBadge = 'bg-warning text-dark';

                let html = `
                    <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                        <div>
                            <span class="text-muted d-block small">ID PESANAN</span>
                            <span class="fw-bold text-dark fs-5">#${data.id_pesanan}</span>
                        </div>
                        <span class="badge ${statusBadge} px-3 py-2 rounded-pill text-uppercase fw-bold">${data.status}</span>
                    </div>

                    <p class="fw-bold text-secondary small text-uppercase mb-2">Menu Yang Dipesan:</p>
                    <div class="list-group list-group-flush border rounded-3 overflow-hidden mb-3">`;
                
                data.menus.forEach(m => {
                    html += `
                        <div class="list-group-item p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">${m.menu}</h6>
                                    <small class="text-muted">Vendor: ${m.vendor}</small>
                                </div>
                                <span class="badge bg-primary-subtle text-primary rounded-pill fw-bold">x${m.jumlah}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-light">
                                <span class="text-muted small">Subtotal</span>
                                <span class="fw-bold text-success">Rp ${Number(m.subtotal).toLocaleString('id-ID')}</span>
                            </div>
                        </div>`;
                });
                
                html += `
                    </div>
                    <div class="d-grid mt-3">
                        <button onclick="window.location.reload()" class="btn btn-primary rounded-pill fw-semibold py-2">
                            🔄 Scan QR Selanjutnya
                        </button>
                    </div>`;
                
                document.getElementById('pesanan-info').innerHTML = html;
            })
            .catch(err => {
                document.getElementById('pesanan-info').innerHTML = `
                    <div class="alert alert-danger border-0 rounded-3 text-center mb-0 p-3" role="alert">
                        <p class="mb-0 fw-semibold text-danger">⚠️ Pesanan tidak ditemukan!</p>
                        <div class="d-grid mt-3">
                            <button onclick="window.location.reload()" class="btn btn-sm btn-danger rounded-pill">Coba Lagi</button>
                        </div>
                    </div>`;
            });
    }

    function onScanFailure(error) {
        console.warn(`Scan gagal: ${error}`);
    }

    let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endpush