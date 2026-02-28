<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.5; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2, .header p { margin: 0; }
        .content { margin-top: 30px; }
        .footer { margin-top: 50px; float: right; width: 250px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>RUMAH SAKIT HEWAN PENDIDIKAN (RSHP)</h2>
        <p>UNIVERSITAS AIRLANGGA</p>
        <p style="font-size: 12px;">Kampus C Mulyorejo, Surabaya. Telp: (031) 5992785</p>
    </div>

    <div class="content">
        <p>Nomor: {{ $nomor }}</p>
        <p>Hal: Undangan Verifikasi Sistem Database</p>
        <br>
        <p>Kepada Yth,<br><strong>Sdr/i. {{ $nama }}</strong></p>
        <br>
        <p>Sehubungan dengan penyelesaian pengembangan sistem <strong>"koleksi_buku"</strong> menggunakan PostgreSQL, kami mengundang Anda untuk hadir dalam sesi demonstrasi sistem.</p>
    </div>

    <div class="footer">
        <p>Surabaya, {{ date('d F Y') }}</p>
        <p>Kepala RSHP UNAIR,</p>
        <br><br><br>
        <p><strong>( ________________ )</strong></p>
    </div>
</body>
</html>