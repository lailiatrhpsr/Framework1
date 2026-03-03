<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; line-height: 1.6; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2, .header p { margin: 0; }
        .content { margin-top: 30px; }
        .footer { margin-top: 60px; float: right; width: 250px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>TOKO BUKU HUSADA UTAMA RAYA</h2>
        <p>CAHAYA HATI</p>
        <p style="font-size: 12px;">Jl. Bakti Darma 100, Surabaya. Telp: (031) 5992785</p>
    </div>

    <div class="content">
        <p>Nomor: {{ $nomor }}</p>
        <p>Hal: Undangan Verifikasi Sistem Database</p>
        <br>
        <p>Kepada Yth,<br><strong>Sdr/i. {{ $nama }}</strong></p>
        <br>
        <p>
            Sehubungan dengan penyelesaian pengembangan sistem <strong>"koleksi_buku"</strong> di Toko Buku Husada Utama Raya,
            kami mengundang Anda untuk hadir dalam sesi demonstrasi sistem yang akan dilaksanakan pada:
        </p>
        <p>
            Hari/Tanggal: {{ $tanggal }} <br>
            Waktu: {{ $waktu }} <br>
            Tempat: {{ $tempat }}
        </p>
        <p>Demikian undangan ini kami sampaikan. Atas perhatian dan kehadirannya, kami ucapkan terima kasih.</p>
    </div>

    <div class="footer">
        <p>Surabaya, {{ date('d F Y') }}</p>
        <p>Kepala Toko Buku Husada Utama Raya,</p>
        <br><br><br>
        <p><strong>( ________________ )</strong></p>
    </div>
</body>
</html>
