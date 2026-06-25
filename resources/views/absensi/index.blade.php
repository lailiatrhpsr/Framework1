<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>📋 Rekap Kehadiran Mahasiswa</h2>
            <a href="/absensi-scanner" class="btn btn-primary" target="_blank">📱 Buka Scanner HP</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Tanggal & Waktu</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Dosen Pengampu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allAbsensi as $index => $absen)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $absen->waktu_hadir }}</td>
                            <td>{{ $absen->mahasiswa->nim }}</td>
                            <td>{{ $absen->mahasiswa->nama }}</td>
                            <td>{{ $absen->dosen->nama }}</td>
                            <td><span class="badge bg-success">{{ $absen->status }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data absensi hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>