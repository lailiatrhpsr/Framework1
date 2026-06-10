@extends('layouts.app')

@section('title', 'Kunjungan Toko')

@section('content')
<div class="container py-4"> 
    <h3 class="mb-4 fw-bold text-dark text-md-start text-center">📍 Menu Kunjungan Toko</h3>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold text-primary mb-3">Scan Barcode Toko</h5>
                    
                    <div class="d-flex flex-column flex-md-row align-items-start gap-4">
                        <div style="width: 100%; max-width: 280px;"> <div id="reader" style="width:100%; min-height:180px;" class="border rounded-3 bg-light"></div>
                        </div>
                        
                        <div class="flex-grow-1 w-100 align-self-stretch d-flex">
                            <div id="toko-info" class="alert alert-secondary w-100 mb-0 d-flex align-items-center">
                                <div>Belum ada barcode yang di-scan.</div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold text-success mb-3">Posisi Sales</h5>
                    
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <div id="sales-info" class="alert alert-secondary mb-0">Lokasi sales belum diambil.</div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column gap-2">
                                <button id="ambil-lokasi" class="btn btn-primary fw-semibold py-2">Ambil Lokasi Terbaik</button>
                                <button id="submit-kunjungan" class="btn btn-success fw-semibold py-2">Submit Kunjungan</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="result" class="mt-2"></div>

    <div class="card shadow-sm border-0 rounded-3 mt-4">
        <div class="card-body p-4">
            <h5 class="card-title fw-bold text-dark mb-3">📜 Riwayat Kunjungan Terbaru</h5>
            <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th>Nama Toko Terdaftar</th>
                            <th>Waktu Kunjungan</th>
                            <th class="text-center">Status Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody id="history-table-body">
                        @forelse($history as $item)
                        <tr>
                            <td>
                                <span class="fw-bold text-dark">{{ $item->toko->nama_toko ?? 'Toko Tidak Diketahui' }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $item->created_at->format('d F Y - H:i') }} WIB</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $item->status === 'DITERIMA' ? 'bg-success' : 'bg-danger' }} px-3 py-2" style="font-size: 11px;">
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr id="empty-row">
                            <td colspan="3" class="text-center text-muted py-4">Belum ada riwayat kunjungan hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<audio id="beep" src="/sounds/beep.mp3"></audio>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let barcodeScanned = null;
let salesPos = null;

// [1] Callback ketika Barcode Sukses terbaca Kamera
function onScanSuccess(decodedText) {
    if (barcodeScanned === decodedText) return; 
    
    document.getElementById('beep').play();
    barcodeScanned = decodedText;
    
    const tokoInfoBox = document.getElementById('toko-info');
    tokoInfoBox.className = "alert alert-warning w-100 mb-0 d-flex align-items-center";
    tokoInfoBox.innerHTML = `<div><strong>Status:</strong> Sedang mencari data toko di database...</div>`;

    fetch(`cek-toko/${decodedText}`)
    .then(async res => {
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Toko gagal ditemukan.');
        return data;
    })
    .then(data => {
        tokoInfoBox.className = "alert alert-success w-100 mb-0";
        tokoInfoBox.innerHTML = `
            <div class="w-100">
                <h5 class="alert-heading fw-bold text-success mb-2">✓ Toko Terverifikasi: ${data.nama_toko}</h5>
                <div class="row g-2 text-dark" style="font-size: 13px;">
                    <div class="col-sm-6"><strong>Barcode / Kode:</strong> <code>${barcodeScanned}</code></div>
                    <div class="col-sm-6"><strong>Akurasi Master:</strong> ${data.accuracy} meter</div>
                    <div class="col-sm-6"><strong>Latitude Toko:</strong> ${data.latitude}</div>
                    <div class="col-sm-6"><strong>Longitude Toko:</strong> ${data.longitude}</div>
                </div>
            </div>
        `;
    })
    .catch(err => {
        barcodeScanned = null; 
        tokoInfoBox.className = "alert alert-danger w-100 mb-0 d-flex align-items-center";
        tokoInfoBox.innerHTML = `<div><strong>❌ Error:</strong> ${err.message}</div>`;
    });
}

function onScanFailure(error) {
    console.warn("Mencari barcode...", error);
}

// Inisialisasi Scanner (qrbox disesuaikan 160 agar proporsional di kotak kecil)
let scanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 160 });
scanner.render(onScanSuccess, onScanFailure);


// [2] Logika GPS Pintar (Mengunci Akurasi Terbaik dalam Max 7 Detik)
async function getAccuratePosition(targetAccuracy = 50, maxWait = 7000) {
    return new Promise((resolve, reject) => {
        let bestResult = null; 
        const startTime = Date.now();
        
        let watchId = navigator.geolocation.watchPosition(
            (position) => {
                let acc = position.coords.accuracy;
                if (!bestResult || acc < bestResult.coords.accuracy) {
                    bestResult = position;
                    document.getElementById('sales-info').innerText = `Sedang mengunci koordinat GPS... (Tingkat akurasi saat ini: ${acc.toFixed(1)}m)`;
                }
                if (acc <= targetAccuracy) {
                    navigator.geolocation.clearWatch(watchId);
                    resolve(bestResult);
                }
            },
            (err) => {
                navigator.geolocation.clearWatch(watchId);
                reject(err);
            },
            { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
        );

        const intervalCheck = setInterval(() => {
            const timePassed = Date.now() - startTime;
            if (bestResult && timePassed >= 3000 && bestResult.coords.accuracy <= 100) {
                clearInterval(intervalCheck);
                navigator.geolocation.clearWatch(watchId);
                resolve(bestResult);
            }
            if (timePassed >= maxWait) {
                clearInterval(intervalCheck);
                navigator.geolocation.clearWatch(watchId);
                if (bestResult) resolve(bestResult); else reject("Sinyal GPS lemah!");
            }
        }, 1000);
    });
}

document.getElementById('ambil-lokasi').addEventListener('click', async () => {
    try {
        document.getElementById('sales-info').className = "alert alert-warning mb-0";
        document.getElementById('sales-info').innerText = "Sedang mencari koordinat GPS presisi...";
        salesPos = await getAccuratePosition(50, 7000); 
        
        document.getElementById('sales-info').className = "alert alert-info mb-0";
        document.getElementById('sales-info').innerHTML = `
            <div class="row g-1 style="font-size: 13px;">
                <div class="col-sm-4"><strong>Latitude Sales:</strong> ${salesPos.coords.latitude}</div>
                <div class="col-sm-4"><strong>Longitude Sales:</strong> ${salesPos.coords.longitude}</div>
                <div class="col-sm-4"><strong>Akurasi Alat:</strong> ${salesPos.coords.accuracy.toFixed(1)} meter</div>
            </div>
        `;
    } catch (err) {
        let pesanError = "Gagal mengambil lokasi.";
        if (err.code === 1) pesanError = "Izin lokasi ditolak browser! Harap aktifkan GPS di pengaturan browser.";
        else if (err.code === 2) pesanError = "Sinyal GPS tidak tersedia.";
        
        alert(pesanError);
        document.getElementById('sales-info').className = "alert alert-danger mb-0";
        document.getElementById('sales-info').innerText = pesanError;
    }
});


// [3] Event Handler Tombol Kirim / Submit Kunjungan ke Backend
document.getElementById('submit-kunjungan').addEventListener('click', () => {
    if (!barcodeScanned || !salesPos) {
        alert("Wajib melakukan scan barcode toko dan mengunci lokasi GPS terlebih dahulu!");
        return;
    }
    
    fetch('/kunjungan/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            barcode: barcodeScanned,
            lat_sales: salesPos.coords.latitude,
            lng_sales: salesPos.coords.longitude,
            accuracy_sales: salesPos.coords.accuracy
        })
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Gagal memproses data server.');
        return data;
    })
    .then(data => {
        let alertColor = data.status === "DITERIMA" ? "alert-success" : "alert-danger";
        let statusBadge = data.status === "DITERIMA" ? "bg-success" : "bg-danger";

        document.getElementById('result').innerHTML = `
            <div class="alert ${alertColor} shadow-sm rounded-3 p-4 mb-4">
                <h4 class="alert-heading fw-bold">Hasil Verifikasi Jarak: <span class="badge ${statusBadge}">${data.status}</span></h4>
                <hr>
                <div class="row style="font-size: 14px;">
                    <div class="col-sm-6 mb-2"><strong>Nama Toko:</strong> ${data.nama_toko}</div>
                    <div class="col-sm-6 mb-2"><strong>Waktu Proses:</strong> ${data.Waktu}</div>
                    <div class="col-sm-6"><strong>Jarak Aktual ke Lokasi:</strong> ${data.jarak} meter</div>
                    <div class="col-sm-6"><strong>Batas Toleransi Maksimal:</strong> ${data.threshold} meter</div>
                </div>
            </div>
        `;

        // INJEKSI BARIS BARU KE TABEL HISTORY LAPTOP
        const emptyRow = document.getElementById('empty-row');
        if (emptyRow) emptyRow.remove(); 

        const tableBody = document.getElementById('history-table-body');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td><span class="fw-bold text-dark">${data.nama_toko}</span></td>
            <td><span class="text-muted">Barusan</span></td>
            <td class="text-center">
                <span class="badge ${statusBadge} px-3 py-2" style="font-size: 11px;">${data.status}</span>
            </td>
        `;
        tableBody.insertBefore(newRow, tableBody.firstChild);
    })
    .catch(err => {
        document.getElementById('result').innerHTML = `
            <div class="alert alert-danger shadow-sm rounded-3 p-3 mb-4"><strong>Error:</strong> ${err.message}</div>
        `;
    });
});
</script>
@endpush