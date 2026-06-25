<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Web NFC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="card shadow-sm mx-auto" style="max-width: 500px;">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0">NFC Attendance Scanner</h4>
            </div>
            <div class="card-body py-4">
                
                <div class="mb-4">
                    <label for="dosen_id" class="form-label fw-bold">Dosen Pengampu Jam Ini:</label>
                    <select id="dosen_id" class="form-select">
                        @forelse($listDosen as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->nama }} (NIP: {{ $dosen->nip }})</option>
                        @endforelse
                    </select>
                </div>

                <button class="btn btn-success btn-lg w-100 mb-4 fw-bold shadow-sm" onclick="startNfcScan()">
                    📱 AKTIFKAN SCANNER NFC
                </button>

                <div class="alert alert-secondary text-center fw-medium" id="status-box">
                    Status: Scanner Belum Aktif.
                </div>
                
                <hr class="my-4">

                <div id="hasil-box"></div>
            </div>
        </div>
    </div>

    <script>
        async function startNfcScan() {
            const statusBox = document.getElementById('status-box');
            const hasilBox = document.getElementById('hasil-box');

            if (!('NDEFReader' in window)) {
                updateStatus('Gagal: Browser tidak mendukung Web NFC. Gunakan Google Chrome di HP Android!', 'danger');
                return;
            }

            try {
                const ndef = new NDEFReader();
                await ndef.scan(); 

                updateStatus('NFC Active! Tempelkan kartu mahasiswa ke bagian belakang HP Anda... ✨', 'info');

                ndef.addEventListener('reading', async ({ serialNumber }) => {
                    updateStatus(`Membaca kartu UID: ${serialNumber}. Memproses ke server...`, 'warning');
                    
                    const selectedDosenId = document.getElementById('dosen_id').value;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    try {
                        const response = await fetch('/absensi-scan', { 
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                nfc_serial: serialNumber,
                                dosen_id: selectedDosenId
                            })
                        });

                        const result = await response.json();

                        if (response.ok && result.success) {
                            updateStatus('Presensi Berhasil Dicatat!', 'success');
                            hasilBox.innerHTML = `
                                <div class="card border-success bg-light text-dark">
                                    <div class="card-body">
                                        <h5 class="card-title text-success fw-bold">✅ ${result.message}</h5>
                                        <p class="card-text mb-1"><b>Nama:</b> ${result.data.nama}</p>
                                        <p class="card-text mb-1"><b>NIM:</b> ${result.data.nim}</p>
                                        <p class="card-text mb-0"><b>Jam Hadir:</b> ${result.data.waktu} WIB</p>
                                    </div>
                                </div>`;
                        } else {
                            updateStatus('Gagal Melakukan Presensi', 'danger');
                            hasilBox.innerHTML = `
                                <div class="alert alert-danger">
                                    <strong>❌ Error:</strong> ${result.message || 'Terjadi kesalahan sistem.'}
                                </div>`;
                        }

                    } catch (fetchError) {
                        updateStatus('Error: Gagal terhubung ke server Laravel.', 'danger');
                        console.error('Fetch Error Log:', fetchError);
                    }
                });

            } catch (err) {
                updateStatus('Error: ' + err.message, 'danger');
                console.error('NFC Initialization Error:', err);
            }
        }

        function updateStatus(message, bootstrapType) {
            const statusBox = document.getElementById('status-box');
            statusBox.textContent = message;
            statusBox.className = `alert alert-${bootstrapType} text-center fw-medium`;
        }
    </script>
</body>
</html>