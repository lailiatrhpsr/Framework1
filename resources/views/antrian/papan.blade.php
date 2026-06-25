<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Papan Antrian — RS Keluarga</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=DM+Sans:wght@500;600;700&display=swap" rel="stylesheet">
  <script>window.addEventListener('load',()=>history.pushState({},'',location.href));</script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; }
    body {
      font-family: 'Inter', sans-serif;
      background: #060E1F;
      color: #E2EAF4;
      min-height: 100vh;
      display: flex; flex-direction: column;
      overflow: hidden;
    }

    /* Background subtle grid */
    body::before {
      content: '';
      position: fixed; inset: 0;
      background-image:
        linear-gradient(rgba(56,139,253,.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(56,139,253,.03) 1px, transparent 1px);
      background-size: 48px 48px;
      pointer-events: none;
    }

    /* ── Header ── */
    header {
      display: flex; align-items: center; justify-content: space-between;
      padding: .85rem 2rem;
      background: rgba(10,20,40,.8);
      border-bottom: 1px solid rgba(56,139,253,.12);
      backdrop-filter: blur(12px);
      position: relative; z-index: 10;
      flex-shrink: 0;
    }
    .h-brand { display: flex; align-items: center; gap: 12px; }
    .h-icon {
      width: 38px; height: 38px; border-radius: 9px;
      background: linear-gradient(135deg, #388BFD, #1668DC);
      display: flex; align-items: center; justify-content: center;
      font-size: 18px; flex-shrink: 0;
      box-shadow: 0 3px 12px rgba(56,139,253,.35);
    }
    .h-name { font-family: 'DM Sans', sans-serif; font-size: 16px; font-weight: 600; color: #E2EAF4; }
    .h-sub  { font-size: 11px; color: #3D5A80; margin-top: 1px; }
    .h-right { display: flex; align-items: center; gap: 1rem; }
    .h-clock { text-align: right; }
    .h-time { font-family: 'DM Sans', sans-serif; font-size: 22px; font-weight: 600; color: #60AFFE; letter-spacing: .02em; }
    .h-date { font-size: 11px; color: #3D5A80; margin-top: 1px; }
    .live-pill {
      display: flex; align-items: center; gap: 5px;
      background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.25);
      color: #FCA5A5; padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 600;
      letter-spacing: .06em;
    }
    .live-dot { width: 6px; height: 6px; border-radius: 50%; background: #EF4444; animation: blink 1s infinite; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.2} }

    /* ── Overlay aktivasi ── */
    #overlay {
      flex: 1; display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      gap: 1rem; text-align: center; padding: 2rem;
      position: relative; z-index: 5;
    }
    .overlay-icon { font-size: 52px; margin-bottom: .5rem; opacity: .6; }
    .overlay-title { font-size: 18px; font-weight: 600; color: #C8D8EE; }
    .overlay-sub { font-size: 13px; color: #3D5A80; max-width: 340px; line-height: 1.65; }
    .btn-aktivasi {
      margin-top: .5rem; padding: 12px 32px;
      background: linear-gradient(135deg, #388BFD, #1668DC);
      border: none; border-radius: 10px;
      color: #fff; font-size: 14px; font-weight: 600;
      font-family: 'Inter', sans-serif; cursor: pointer;
      box-shadow: 0 4px 20px rgba(56,139,253,.4);
      transition: opacity .15s, transform .1s;
    }
    .btn-aktivasi:hover { opacity: .92; transform: translateY(-1px); }

    /* ── Papan utama ── */
    #papan {
      flex: 1; display: none;
      padding: 1.25rem 1.5rem;
      gap: 1.25rem;
      position: relative; z-index: 5;
      overflow: hidden;
    }
    #papan.active { display: grid; grid-template-columns: 1fr 340px; grid-template-rows: 1fr auto; }
    @media(max-width:800px) { #papan.active { grid-template-columns: 1fr; grid-template-rows: auto auto auto; } }

    /* ── Now Calling (kiri) ── */
    .now-card {
      background: linear-gradient(160deg, #0D2247 0%, #091A38 60%, #060E1F 100%);
      border: 1px solid rgba(56,139,253,.2);
      border-radius: 20px;
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      padding: 2.5rem 2rem; text-align: center;
      position: relative; overflow: hidden;
      grid-row: 1;
    }
    /* Decorative glow */
    .now-card::before {
      content: '';
      position: absolute; top: -80px; left: 50%; transform: translateX(-50%);
      width: 400px; height: 300px;
      background: radial-gradient(ellipse, rgba(56,139,253,.15) 0%, transparent 70%);
      pointer-events: none;
    }
    .now-eyebrow {
      font-size: 11px; font-weight: 600; letter-spacing: .14em;
      text-transform: uppercase; color: #3D6899;
      margin-bottom: 1rem; position: relative;
    }
    .now-num {
      font-family: 'DM Sans', sans-serif;
      font-size: clamp(100px, 18vw, 160px);
      font-weight: 700; color: #60AFFE;
      line-height: 1; letter-spacing: -.04em;
      position: relative;
    }
    .now-num.ring { animation: ringAnim .5s ease-out; }
    @keyframes ringAnim {
      0%  { transform: scale(1); filter: brightness(1); }
      30% { transform: scale(1.06); filter: brightness(1.3); }
      70% { transform: scale(.98); }
      100%{ transform: scale(1); filter: brightness(1); }
    }
    .now-divider {
      width: 40px; height: 2px; background: rgba(56,139,253,.3);
      border-radius: 2px; margin: 1.25rem auto;
    }
    .now-name {
      font-family: 'DM Sans', sans-serif;
      font-size: clamp(20px, 3vw, 28px);
      font-weight: 600; color: #E2EAF4; letter-spacing: -.01em;
    }
    .now-loket {
      display: inline-block; margin-top: .9rem;
      padding: 7px 20px;
      background: rgba(56,139,253,.15);
      border: 1px solid rgba(56,139,253,.3);
      border-radius: 99px; font-size: 14px; color: #60AFFE;
    }
    .now-idle { font-size: 15px; color: #2E4566; font-style: italic; }

    /* ── Sidebar kanan ── */
    .sidebar {
      display: flex; flex-direction: column; gap: 1rem;
      grid-row: 1; overflow: hidden;
    }

    /* Antrian Menunggu */
    .waiting-card {
      background: rgba(10,20,40,.7);
      border: 1px solid rgba(56,139,253,.12);
      border-radius: 16px; overflow: hidden;
      flex: 1; display: flex; flex-direction: column;
    }
    .wc-head {
      padding: 12px 16px; border-bottom: 1px solid rgba(56,139,253,.1);
      display: flex; align-items: center; justify-content: space-between;
    }
    .wc-title { font-size: 11px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: #3D5A80; }
    .wc-count { font-size: 12px; font-weight: 600; background: rgba(56,139,253,.15); color: #60AFFE; padding: 2px 10px; border-radius: 99px; }
    .wc-list { flex: 1; overflow-y: auto; padding: .5rem 0; }
    .wc-item {
      display: flex; align-items: center; gap: 12px;
      padding: 10px 16px; transition: background .1s;
    }
    .wc-item:hover { background: rgba(56,139,253,.06); }
    .wc-item-num {
      font-family: 'DM Sans', sans-serif;
      font-size: 18px; font-weight: 700; color: #2E5490;
      min-width: 44px;
    }
    .wc-item-info { flex: 1; min-width: 0; }
    .wc-item-name { font-size: 13px; font-weight: 500; color: #8BA8C8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .wc-item-loket { font-size: 11px; color: #2E4566; margin-top: 1px; }
    .wc-empty { text-align: center; padding: 2rem; font-size: 13px; color: #1E3355; }

    /* Stat strip bawah */
    .stat-strip {
      background: rgba(10,20,40,.7);
      border: 1px solid rgba(56,139,253,.12);
      border-radius: 12px; padding: .85rem 1.25rem;
      display: flex; align-items: center; justify-content: space-around;
      grid-column: 1 / -1;
    }
    .ss-item { text-align: center; }
    .ss-num { font-family: 'DM Sans', sans-serif; font-size: 22px; font-weight: 700; }
    .ss-lbl { font-size: 10px; letter-spacing: .08em; text-transform: uppercase; color: #2E4566; margin-top: 2px; }
    .ss-menunggu  .ss-num { color: #388BFD; }
    .ss-dipanggil .ss-num { color: #F59E0B; }
    .ss-selesai   .ss-num { color: #22C55E; }
    .ss-divider { width: 1px; height: 32px; background: rgba(56,139,253,.1); }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(56,139,253,.2); border-radius: 4px; }

    /* Footer */
    .conn-bar {
      text-align: center; font-size: 11px; color: #1E3355;
      padding: 5px; background: #060E1F; flex-shrink: 0;
      border-top: 1px solid rgba(56,139,253,.06);
    }
  </style>
</head>
<body>

<header>
  <div class="h-brand">
    <div class="h-icon">🏥</div>
    <div>
      <div class="h-name">RS Keluarga</div>
      <div class="h-sub">Sistem Antrian Digital Terpadu</div>
    </div>
  </div>
  <div class="h-right">
    <div class="h-clock">
      <div class="h-time" id="jam">—</div>
      <div class="h-date" id="tgl">—</div>
    </div>
    <div class="live-pill"><span class="live-dot"></span> LIVE</div>
  </div>
</header>

<div id="overlay">
  <div class="overlay-icon">🔊</div>
  <div class="overlay-title">Aktifkan Papan Antrian</div>
  <p class="overlay-sub">Klik tombol di bawah untuk menampilkan papan dan mengaktifkan pengumuman suara otomatis.</p>
  <button class="btn-aktivasi" onclick="aktivasiPapan()">▶ Aktifkan Sekarang</button>
</div>

<!-- Papan -->
<div id="papan">
  <div class="now-card">
    <div class="now-eyebrow">Nomor Dipanggil</div>
    <div class="now-num" id="now-num">—</div>
    <div class="now-divider"></div>
    <div class="now-name"  id="now-name">Menunggu panggilan…</div>
    <div id="now-loket-wrap" style="display:none">
      <div class="now-loket" id="now-loket">—</div>
    </div>
  </div>

  <div class="sidebar">
    <div class="waiting-card">
      <div class="wc-head">
        <span class="wc-title">Antrian Menunggu</span>
        <span class="wc-count" id="wc-count">0</span>
      </div>
      <div class="wc-list" id="wc-list">
        <div class="wc-empty">Tidak ada antrian</div>
      </div>
    </div>
  </div>

  <div class="stat-strip">
    <div class="ss-item ss-menunggu">
      <div class="ss-num" id="ss-menunggu">0</div>
      <div class="ss-lbl">Menunggu</div>
    </div>
    <div class="ss-divider"></div>
    <div class="ss-item ss-dipanggil">
      <div class="ss-num" id="ss-dipanggil">0</div>
      <div class="ss-lbl">Dipanggil</div>
    </div>
    <div class="ss-divider"></div>
    <div class="ss-item ss-selesai">
      <div class="ss-num" id="ss-selesai">0</div>
      <div class="ss-lbl">Selesai</div>
    </div>
  </div>

</div>

<div class="conn-bar" id="conn-bar">Menghubungkan ke server…</div>

<script>
let lastTimestamp = 0;
let papanAktif = false;

function updateJam() {
  const now = new Date();
  document.getElementById('jam').textContent =
    now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
  document.getElementById('tgl').textContent =
    now.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
}
updateJam(); setInterval(updateJam, 1000);

// ── Aktivasi ──
function aktivasiPapan() {
  papanAktif = true;
  document.getElementById('overlay').style.display = 'none';
  document.getElementById('papan').classList.add('active');
  const dummy = new SpeechSynthesisUtterance('');
  window.speechSynthesis.speak(dummy);
  startPolling();
}

// ── Polling ──
function startPolling() {
  fetchData(); setInterval(fetchData, 2000);
}

async function fetchData() {
  try {
    const res = await fetch('/antrian/data');
    if (!res.ok) throw new Error();
    const data = await res.json();
    renderPapan(data);
    document.getElementById('conn-bar').textContent = '● Terhubung ke server';
  } catch {
    document.getElementById('conn-bar').textContent = '⚠ Koneksi terputus, mencoba ulang…';
  }
}

// ── Render ──
function renderPapan(data) {
  const cur = data.current;

  // Stats strip
  document.getElementById('ss-menunggu').textContent  = data.stat.menunggu;
  document.getElementById('ss-dipanggil').textContent = data.stat.dipanggil;
  document.getElementById('ss-selesai').textContent   = data.stat.selesai ?? 0;

  // Now calling
  if (cur) {
    const isNew = cur.timestamp > lastTimestamp;
    const numEl = document.getElementById('now-num');

    numEl.textContent = String(cur.nomor_urut).padStart(3, '0');
    document.getElementById('now-name').textContent = cur.nama;

    const loketWrap = document.getElementById('now-loket-wrap');
    if (cur.loket) {
      document.getElementById('now-loket').textContent = '📍 Poli ' + cur.loket;
      loketWrap.style.display = 'block';
    } else {
      loketWrap.style.display = 'none';
    }

    if (isNew) {
      numEl.classList.remove('ring');
      void numEl.offsetWidth;
      numEl.classList.add('ring');
      if (papanAktif) bunyikan(cur);
      lastTimestamp = cur.timestamp;
    }
  } else {
    document.getElementById('now-num').textContent  = '—';
    document.getElementById('now-name').textContent = 'Menunggu panggilan…';
    document.getElementById('now-loket-wrap').style.display = 'none';
  }

  // Waiting list (sidebar)
  const menunggu = data.menunggu ?? [];
  document.getElementById('wc-count').textContent = menunggu.length;
  const list = document.getElementById('wc-list');

  const filtered = cur ? menunggu.filter(e => e.nomor_urut !== cur.nomor_urut) : menunggu;

  if (filtered.length === 0) {
    list.innerHTML = '<div class="wc-empty">Tidak ada antrian menunggu</div>';
  } else {
    list.innerHTML = filtered.map(e => `
      <div class="wc-item">
        <div class="wc-item-num">${String(e.nomor_urut).padStart(3,'0')}</div>
        <div class="wc-item-info">
          <div class="wc-item-name">${escHtml(e.nama)}</div>
          <div class="wc-item-loket">${escHtml(e.loket ?? '—')}</div>
        </div>
      </div>
    `).join('');
  }
}

// ── TTS ──
function bunyikan(cur) {
  if (!('speechSynthesis' in window)) return;
  window.speechSynthesis.cancel();
  const nomorFormat = String(cur.nomor_urut).padStart(3, '0');
  const nomorDieja = nomorFormat.split('').map(digit => digit === '0' ? 'nol' : digit).join(' ');
  let loketSuara = cur.loket ?? 'loket pendaftaran';
  if (cur.loket && !cur.loket.toLowerCase().startsWith('poli')) {
    loketSuara = 'Poli ' + cur.loket;
  }
  const teks = `Nomor antrian, ${nomorDieja}. ${cur.nama}. Silakan menuju ${loketSuara}.`;
  
  const u = new SpeechSynthesisUtterance(teks);
  u.lang = 'id-ID'; 
  u.rate = 0.82; 
  u.pitch = 1.0; 
  u.volume = 1.0;
  
  setTimeout(() => window.speechSynthesis.speak(u), 400);
}

function escHtml(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
</script>
</body>
</html>