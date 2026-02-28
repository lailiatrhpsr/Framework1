<!DOCTYPE html>
<html>
<head>
    <style>
        @page { size: a4 landscape; margin: 0; }
        body { font-family: 'Georgia', serif; text-align: center; padding: 50px; border: 20px solid #1a237e; height: 100%; }
        .title { font-size: 50px; color: #1a237e; margin-top: 50px; }
        .name { font-size: 35px; font-weight: bold; margin: 30px 0; border-bottom: 2px solid #333; display: inline-block; padding: 0 50px; }
        .sub-title { font-size: 20px; }
    </style>
</head>
<body>
    <div class="title">SERTIFIKAT</div>
    <div class="sub-title">Diberikan secara bangga kepada:</div>
    <div class="name">{{ $nama }}</div>
    <p>Atas pencapaian luar biasa dalam menyelesaikan:</p>
    <h3>{{ $judul }}</h3>
    <p>Diterbitkan pada: {{ date('d F Y') }}</p>
</body>
</html>