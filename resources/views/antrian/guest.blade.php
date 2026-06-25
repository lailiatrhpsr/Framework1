<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pendaftaran Antrian — RS Keluarga</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      background: #0B1120;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      position: relative;
      overflow: hidden;
    }

    /* Background grid */
    body::before {
      content: '';
      position: fixed; inset: 0;
      background-image:
        linear-gradient(rgba(56,189,248,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(56,189,248,.04) 1px, transparent 1px);
      background-size: 40px 40px;
      pointer-events: none;
    }

    /* Glow top */
    body::after {
      content: '';
      position: fixed;
      top: -160px; left: 50%; transform: translateX(-50%);
      width: 700px; height: 360px;
      background: radial-gradient(ellipse, rgba(56,139,253,.18) 0%, transparent 70%);
      pointer-events: none;
    }

    /* Header */
    .site-header {
      text-align: center;
      margin-bottom: 2rem;
      position: relative;
      z-index: 1;
    }
    .site-logo {
      display: inline-flex; align-items: center; gap: 10px;
      margin-bottom: .5rem;
    }
    .logo-mark {
      width: 40px; height: 40px;
      background: linear-gradient(135deg, #388BFD, #60AFFE);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 20px;
      box-shadow: 0 4px 16px rgba(56,139,253,.4);
    }
    .site-name {
      font-family: 'DM Sans', sans-serif;
      font-size: 20px; font-weight: 600; color: #F0F6FF;
      letter-spacing: -.02em;
    }
    .site-sub {
      font-size: 13px; color: #4B6385;
      letter-spacing: .04em; text-transform: uppercase;
    }

    /* Card */
    .card {
      background: rgba(15,23,42,.85);
      border: 1px solid rgba(56,139,253,.18);
      border-radius: 20px;
      padding: 2.25rem 2rem;
      width: 100%; max-width: 420px;
      backdrop-filter: blur(16px);
      position: relative; z-index: 1;
      box-shadow: 0 24px 64px rgba(0,0,0,.5), inset 0 1px 0 rgba(255,255,255,.05);
    }

    .card-title { font-size: 17px; font-weight: 600; color: #E2EAF4; margin-bottom: .3rem; }
    .card-sub   { font-size: 13px; color: #4B6385; margin-bottom: 1.75rem; }

    /* Form */
    .form-group { margin-bottom: 1.1rem; }
    label {
      display: block; font-size: 12px; font-weight: 500;
      color: #6B88A8; letter-spacing: .06em; text-transform: uppercase;
      margin-bottom: 7px;
    }
    input, select {
      width: 100%; padding: 11px 14px;
      background: rgba(255,255,255,.04);
      border: 1px solid rgba(56,139,253,.2);
      border-radius: 10px;
      font-size: 14px; color: #E2EAF4;
      font-family: 'Inter', sans-serif;
      transition: border-color .15s, box-shadow .15s;
      appearance: none;
    }
    input::placeholder { color: #2E4566; }
    select option { background: #0F1729; }
    input:focus, select:focus {
      outline: none;
      border-color: rgba(56,139,253,.6);
      box-shadow: 0 0 0 3px rgba(56,139,253,.12);
    }
    .select-wrap { position: relative; }
    .select-wrap::after {
      content: '▾'; position: absolute; right: 14px; top: 50%;
      transform: translateY(-50%); color: #4B6385; pointer-events: none;
      font-size: 12px;
    }

    /* Button */
    .btn-submit {
      width: 100%; padding: 13px;
      background: linear-gradient(135deg, #388BFD, #1668DC);
      border: none; border-radius: 10px;
      color: #fff; font-size: 14px; font-weight: 600;
      font-family: 'Inter', sans-serif;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: opacity .15s, transform .1s;
      box-shadow: 0 4px 20px rgba(56,139,253,.35);
      margin-top: 1.5rem;
    }
    .btn-submit:hover:not(:disabled) { opacity: .92; transform: translateY(-1px); }
    .btn-submit:active:not(:disabled) { transform: translateY(0); }
    .btn-submit:disabled { opacity: .5; cursor: not-allowed; }

    /* Error */
    .alert {
      display: none; padding: 10px 13px; border-radius: 8px;
      font-size: 13px; margin-bottom: 1rem;
      background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.3);
      color: #FCA5A5;
    }

    /* ── Tiket ── */
    #tiket { display: none; }
    .success-icon {
      width: 52px; height: 52px; border-radius: 50%;
      background: rgba(16,185,129,.15); border: 1.5px solid rgba(16,185,129,.4);
      display: flex; align-items: center; justify-content: center;
      font-size: 22px; margin: 0 auto 1rem;
    }
    .success-title { text-align: center; font-size: 16px; font-weight: 600; color: #E2EAF4; margin-bottom: 1.5rem; }

    .ticket-box {
      border: 1.5px dashed rgba(56,139,253,.3);
      border-radius: 14px;
      overflow: hidden;
    }
    .ticket-top {
      background: linear-gradient(135deg, #0D2247, #0F2D5A);
      padding: 1.5rem; text-align: center;
      border-bottom: 1.5px dashed rgba(56,139,253,.3);
    }
    .ticket-label {
      font-size: 10px; font-weight: 600; letter-spacing: .14em;
      text-transform: uppercase; color: #4B80C4; margin-bottom: .5rem;
    }
    .ticket-num {
      font-family: 'DM Sans', sans-serif;
      font-size: 86px; font-weight: 700; color: #60AFFE;
      line-height: 1; letter-spacing: -.04em;
    }
    .ticket-bottom {
      background: rgba(255,255,255,.03);
      padding: 1.25rem 1.5rem;
    }
    .ticket-row {
      display: flex; justify-content: space-between; align-items: baseline;
      padding: 5px 0; border-bottom: 1px solid rgba(255,255,255,.05);
    }
    .ticket-row:last-child { border-bottom: none; }
    .ticket-key { font-size: 12px; color: #4B6385; }
    .ticket-val { font-size: 13px; font-weight: 500; color: #C8D8EE; text-align: right; }

    .ticket-badge {
      display: block; text-align: center; margin-top: 1rem;
      padding: 8px; background: rgba(56,139,253,.1);
      border-radius: 8px; font-size: 12px; color: #60AFFE;
    }

    .btn-secondary {
      width: 100%; padding: 10px; margin-top: .75rem;
      background: transparent; border: 1px solid rgba(255,255,255,.1);
      border-radius: 10px; color: #6B88A8; font-size: 13px;
      font-family: 'Inter', sans-serif; cursor: pointer;
      transition: border-color .15s, color .15s;
    }
    .btn-secondary:hover { border-color: rgba(255,255,255,.25); color: #C8D8EE; }

    /* Footer */
    .footer {
      margin-top: 1.5rem; text-align: center;
      font-size: 12px; color: #243552; position: relative; z-index: 1;
    }

    /* Spinner */
    .spinner {
      width: 16px; height: 16px;
      border: 2px solid rgba(255,255,255,.3); border-top-color: #fff;
      border-radius: 50%; animation: spin .6s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>
</head>
<body>

<header class="site-header">
  <div class="site-logo">
    <div class="logo-mark">🏥</div>
    <span class="site-name">RS Keluarga</span>
  </div>
  <div class="site-sub">Sistem Antrian Digital</div>
</header>

<div class="card">

  <!-- Form Section -->
  <div id="form-section">
    <div class="card-title">Ambil Nomor Antrian</div>
    <div class="card-sub">Isi data diri untuk mendapatkan nomor antrian</div>

    <div class="alert" id="alert-error"></div>

    <div class="form-group">
      <label for="nama">Nama Lengkap</label>
      <input id="nama" type="text" placeholder="Masukkan nama lengkap Anda" autocomplete="name">
    </div>

    <div class="form-group">
      <label for="loket">Poli Tujuan</label>
      <div class="select-wrap">
        <select id="loket">
          <option value="">-- Pilih Poli --</option>
          <option value="Umum">Poli Umum</option>
          <option value="Kandungan">Poli Kandungan</option>
          <option value="Gigi">Poli Gigi</option>
          <option value="THT">Poli THT</option>
        </select>
      </div>
    </div>

    <button class="btn-submit" id="btn-daftar" onclick="daftarAntrian()">
      <span id="btn-icon">🎫</span>
      <span id="btn-text">Ambil Nomor Antrian</span>
    </button>
  </div>

  <!-- Tiket Section -->
  <div id="tiket">
    <div class="success-icon">✓</div>
    <div class="success-title">Pendaftaran Berhasil</div>

    <div class="ticket-box">
      <div class="ticket-top">
        <div class="ticket-label">Nomor Antrian Anda</div>
        <div class="ticket-num" id="t-nomor">—</div>
      </div>
      <div class="ticket-bottom">
        <div class="ticket-row">
          <span class="ticket-key">Nama</span>
          <span class="ticket-val" id="t-nama">—</span>
        </div>
        <div class="ticket-row">
          <span class="ticket-key">Poli</span>
          <span class="ticket-val" id="t-loket">—</span>
        </div>
        <div class="ticket-row">
          <span class="ticket-key">Waktu Daftar</span>
          <span class="ticket-val" id="t-waktu">—</span>
        </div>
        <div class="ticket-row">
          <span class="ticket-key">Posisi Antrian</span>
          <span class="ticket-val" id="t-posisi">—</span>
        </div>
        <div class="ticket-badge" id="t-estimasi">Memuat estimasi…</div>
      </div>
    </div>

    <p style="text-align:center;font-size:12px;color:#4B6385;margin-top:1rem;line-height:1.6">
      Harap menunggu di ruang tunggu.<br>Nama Anda akan dipanggil melalui papan antrian.
    </p>

    <button class="btn-secondary" onclick="daftarBaru()">+ Daftarkan Pasien Lain</button>
  </div>

</div>

<div class="footer">© 2026 RS Keluarga — Sistem Antrian Digital</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

async function daftarAntrian() {
  const nama  = document.getElementById('nama').value.trim();
  const loket = document.getElementById('loket').value;
  const alert = document.getElementById('alert-error');
  const btn   = document.getElementById('btn-daftar');
  alert.style.display = 'none';

  if (!nama)  { showErr('Nama tidak boleh kosong.'); return; }
  if (!loket) { showErr('Pilih Poli tujuan terlebih dahulu.'); return; }

  btn.disabled = true;
  document.getElementById('btn-icon').outerHTML = '<span id="btn-icon"><div class="spinner"></div></span>';
  document.getElementById('btn-text').textContent = 'Mendaftarkan…';

  try {
    const res = await fetch('/antrian/daftar', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify({ nama, loket }),
    });
    if (!res.ok) { const e = await res.json(); showErr(e.message ?? 'Gagal mendaftar.'); return; }
    const data = await res.json();

    document.getElementById('t-nomor').textContent  = String(data.nomor_urut).padStart(3, '0');
    document.getElementById('t-nama').textContent   = data.nama;
    document.getElementById('t-loket').textContent  = data.loket;
    document.getElementById('t-waktu').textContent  = new Date().toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});
    document.getElementById('t-posisi').textContent = `Ke-${data.posisi} di ${data.loket}`;
    document.getElementById('t-estimasi').textContent = `Estimasi tunggu ± ${data.estimasi} menit`;

    document.getElementById('form-section').style.display = 'none';
    document.getElementById('tiket').style.display = 'block';
  } catch {
    showErr('Terjadi kesalahan jaringan. Periksa koneksi Anda.');
  } finally {
    btn.disabled = false;
    document.getElementById('btn-icon').outerHTML = '<span id="btn-icon">🎫</span>';
    document.getElementById('btn-text').textContent = 'Ambil Nomor Antrian';
  }
}

function daftarBaru() {
  document.getElementById('nama').value  = '';
  document.getElementById('loket').value = '';
  document.getElementById('tiket').style.display = 'none';
  document.getElementById('form-section').style.display = 'block';
}

function showErr(msg) {
  const el = document.getElementById('alert-error');
  el.textContent = msg; el.style.display = 'block';
}

document.getElementById('nama').addEventListener('keydown', e => { if (e.key === 'Enter') daftarAntrian(); });
</script>
</body>
</html>