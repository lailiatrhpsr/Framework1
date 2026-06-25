<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin — Sistem Antrian RS</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Sans:wght@500;600;700&display=swap" rel="stylesheet">
  <script>window.addEventListener('load',()=>history.pushState({},'',location.href));</script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: #F0F4F9; min-height: 100vh; color: #1A2535; }

    /* ── Navbar ── */
    nav {
      background: #fff; border-bottom: 1px solid #E2EAF4;
      height: 56px; padding: 0 1.5rem;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 100;
    }
    .nav-brand { display: flex; align-items: center; gap: 10px; }
    .nav-icon {
      width: 32px; height: 32px; background: #388BFD; border-radius: 8px;
      display: flex; align-items: center; justify-content: center; font-size: 16px;
    }
    .nav-title { font-size: 15px; font-weight: 600; color: #1A2535; }
    .nav-right { display: flex; align-items: center; gap: 10px; }
    .conn-pill {
      display: flex; align-items: center; gap: 5px;
      font-size: 12px; padding: 4px 10px; border-radius: 99px;
      background: #F0F9F4; color: #16A34A; border: 1px solid #BBF7D0;
    }
    .conn-dot { width: 6px; height: 6px; border-radius: 50%; background: #22C55E; animation: pulse 1.4s infinite; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }
    .nav-link {
      font-size: 13px; color: #5B7A9A; text-decoration: none; padding: 5px 10px;
      border-radius: 6px; border: 1px solid #E2EAF4; transition: background .1s;
    }
    .nav-link:hover { background: #F0F4F9; }

    /* ── Layout ── */
    main { max-width: 1100px; margin: 0 auto; padding: 1.5rem; }

    /* ── Sedang Dipanggil Banner ── */
    .calling-banner {
      background: linear-gradient(135deg, #1A3A6B, #0F2550);
      border-radius: 16px; padding: 1.25rem 1.75rem;
      display: flex; align-items: center; gap: 1.5rem;
      margin-bottom: 1.25rem;
      box-shadow: 0 4px 20px rgba(26,58,107,.25);
    }
    .calling-num {
      font-family: 'DM Sans', sans-serif;
      font-size: 52px; font-weight: 700; color: #60AFFE;
      line-height: 1; min-width: 100px; letter-spacing: -.03em;
    }
    .calling-info { flex: 1; }
    .calling-name { font-size: 20px; font-weight: 600; color: #E2EAF4; }
    .calling-loket { font-size: 13px; color: #4B80C4; margin-top: 3px; }
    .calling-badge {
      display: inline-block; padding: 4px 12px; border-radius: 99px;
      font-size: 12px; font-weight: 600; background: #F59E0B; color: #fff;
      margin-top: 6px;
    }
    .calling-empty { color: #4B6385; font-size: 14px; }

    /* ── Stats grid ── */
    .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 1.25rem; }
    @media(max-width:700px) { .stats { grid-template-columns: repeat(2,1fr); } }
    .stat {
      border-radius: 12px; padding: 1rem 1.25rem;
      display: flex; align-items: center; gap: 12px;
    }
    .stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .stat-num  { font-family: 'DM Sans', sans-serif; font-size: 28px; font-weight: 700; line-height: 1; }
    .stat-lbl  { font-size: 12px; margin-top: 1px; }
    .stat-menunggu  { background: #EFF6FF; } .stat-menunggu  .stat-icon { background: #DBEAFE; } .stat-menunggu  .stat-num { color: #1D4ED8; } .stat-menunggu  .stat-lbl { color: #93C5FD; }
    .stat-dipanggil { background: #FFFBEB; } .stat-dipanggil .stat-icon { background: #FEF3C7; } .stat-dipanggil .stat-num { color: #D97706; } .stat-dipanggil .stat-lbl { color: #FCD34D; }
    .stat-terlewat  { background: #FFF1F2; } .stat-terlewat  .stat-icon { background: #FFE4E6; } .stat-terlewat  .stat-num { color: #E11D48; } .stat-terlewat  .stat-lbl { color: #FDA4AF; }
    .stat-selesai   { background: #F0FDF4; } .stat-selesai   .stat-icon { background: #DCFCE7; } .stat-selesai   .stat-num { color: #16A34A; } .stat-selesai   .stat-lbl { color: #86EFAC; }

    /* ── Actions ── */
    .actions { display: flex; gap: 10px; margin-bottom: 1.25rem; flex-wrap: wrap; }
    .btn-panggil {
      flex: 1; min-width: 200px; padding: 13px 20px;
      background: #388BFD; border: none; border-radius: 10px;
      color: #fff; font-size: 14px; font-weight: 600;
      font-family: 'Inter', sans-serif; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: background .15s; box-shadow: 0 4px 14px rgba(56,139,253,.3);
    }
    .btn-panggil:hover:not(:disabled) { background: #1668DC; }
    .btn-panggil:disabled { background: #CBD5E1; box-shadow: none; cursor: not-allowed; }
    .loket-sel {
      padding: 0 14px; border: 1px solid #E2EAF4; border-radius: 10px;
      font-size: 13px; font-family: 'Inter', sans-serif; color: #1A2535;
      background: #fff; min-width: 140px; cursor: pointer;
    }

    /* ── Table ── */
    .table-card {
      background: #fff; border-radius: 14px; border: 1px solid #E2EAF4;
      overflow: hidden;
    }
    .table-head {
      padding: 14px 18px; border-bottom: 1px solid #F1F5F9;
      display: flex; align-items: center; justify-content: space-between;
      background: #FAFCFF;
    }
    .table-head-title { font-size: 14px; font-weight: 600; color: #1A2535; }
    .count-badge {
      font-size: 12px; font-weight: 600; padding: 2px 10px;
      border-radius: 99px; background: #EFF6FF; color: #1D4ED8;
    }
    table { width: 100%; border-collapse: collapse; }
    thead th {
      padding: 10px 18px; text-align: left; font-size: 11px; font-weight: 600;
      color: #94A3B8; letter-spacing: .06em; text-transform: uppercase;
      border-bottom: 1px solid #F1F5F9; background: #FAFCFF;
    }
    tbody tr { border-bottom: 1px solid #F8FAFC; transition: background .1s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #F8FAFF; }
    tbody td { padding: 13px 18px; font-size: 13px; color: #334155; vertical-align: middle; }
    .td-num { font-family: 'DM Sans', sans-serif; font-size: 16px; font-weight: 700; color: #1A2535; }

    /* Status badges */
    .badge { display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; }
    .badge-menunggu  { background: #EFF6FF; color: #1D4ED8; }
    .badge-dipanggil { background: #FFFBEB; color: #B45309; }
    .badge-terlewat  { background: #FFF1F2; color: #BE123C; }
    .badge-selesai   { background: #F0FDF4; color: #15803D; }

    /* Action buttons */
    .act-btn {
      width: 30px; height: 30px; border-radius: 7px; border: none;
      cursor: pointer; display: inline-flex; align-items: center; justify-content: center;
      font-size: 14px; margin-right: 4px; transition: opacity .1s;
    }
    .act-btn:hover { opacity: .8; }
    .act-panggil  { background: #EFF6FF; }
    .act-selesai  { background: #F0FDF4; }
    .act-terlewat { background: #FFF1F2; }

    /* Empty */
    .empty-row td { text-align: center; padding: 2.5rem; color: #94A3B8; font-size: 13px; }

    /* Toast */
    #toast {
      position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%);
      background: #1A2535; color: #fff; padding: 10px 20px;
      border-radius: 8px; font-size: 13px; opacity: 0; pointer-events: none;
      transition: opacity .25s; z-index: 999; white-space: nowrap;
    }
    #toast.show { opacity: 1; }
    .spinner { display:inline-block;width:16px;height:16px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite; }
    @keyframes spin { to { transform:rotate(360deg); } }
  </style>
</head>
<body>

<nav>
  <div class="nav-brand">
    <div class="nav-icon">🏥</div>
    <span class="nav-title">RS Keluarga — Panel Admin</span>
  </div>
  <div class="nav-right">
    <div class="conn-pill"><span class="conn-dot" id="conn-dot"></span><span id="conn-status">Menghubungkan…</span></div>
    <a class="nav-link" href="/papan" target="_blank">📺 Papan Antrian</a>
    <a class="nav-link" href="/guest" target="_blank">👤 Halaman Tamu</a>
  </div>
</nav>

<main>

  <!-- Banner Sedang Dipanggil -->
  <div class="calling-banner" id="calling-banner">
    <div class="calling-num" id="c-num">—</div>
    <div class="calling-info">
      <div class="calling-empty" id="c-empty">Belum ada yang dipanggil hari ini</div>
      <div id="c-detail" style="display:none">
        <div class="calling-name" id="c-name">—</div>
        <div class="calling-loket" id="c-loket">—</div>
        <span class="calling-badge">Sedang Dipanggil</span>
      </div>
    </div>
  </div>

  <!-- Statistik -->
  <div class="stats">
    <div class="stat stat-menunggu">
      <div class="stat-icon">⏳</div>
      <div><div class="stat-num" id="s-menunggu">0</div><div class="stat-lbl">Menunggu</div></div>
    </div>
    <div class="stat stat-dipanggil">
      <div class="stat-icon">📢</div>
      <div><div class="stat-num" id="s-dipanggil">0</div><div class="stat-lbl">Dipanggil</div></div>
    </div>
    <div class="stat stat-terlewat">
      <div class="stat-icon">⏰</div>
      <div><div class="stat-num" id="s-terlewat">0</div><div class="stat-lbl">Terlewat</div></div>
    </div>
    <div class="stat stat-selesai">
      <div class="stat-icon">✅</div>
      <div><div class="stat-num" id="s-selesai">0</div><div class="stat-lbl">Selesai</div></div>
    </div>
  </div>

  <!-- Aksi -->
  <div class="actions">
    <select class="loket-sel" id="sel-loket">
      <option value="Umum">Umum</option>
      <option value="Kandungan">Kandungan</option>
      <option value="Gigi">Gigi</option>
      <option value="THT">THT</option>
    </select>
    <button class="btn-panggil" id="btn-panggil" onclick="panggilBerikutnya()" disabled>
      📢 Panggil Berikutnya
    </button>
  </div>

  <!-- Tabel -->
  <div class="table-card">
    <div class="table-head">
      <span class="table-head-title">Daftar Antrian Hari Ini</span>
      <span class="count-badge" id="count-badge">0 antrian</span>
    </div>
    <div style="overflow-x:auto">
      <table>
        <thead>
          <tr>
            <th>No.</th>
            <th>Nama Pasien</th>
            <th>Poli</th>
            <th>Jam Daftar</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tabel-antrian">
          <tr class="empty-row"><td colspan="6">Memuat data…</td></tr>
        </tbody>
      </table>
    </div>
  </div>

</main>

<div id="toast"></div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

async function fetchData() {
  try {
    const res = await fetch('/antrian/data');
    if (!res.ok) throw new Error();
    const data = await res.json();
    renderUI(data);
    document.getElementById('conn-status').textContent = 'Terhubung';
    document.getElementById('conn-dot').style.background = '#22C55E';
  } catch {
    document.getElementById('conn-status').textContent = 'Mencoba ulang…';
    document.getElementById('conn-dot').style.background = '#F87171';
  }
}
function connectSSE() { fetchData(); setInterval(fetchData, 2000); }

function renderUI(data) {
  document.getElementById('s-menunggu').textContent  = data.stat.menunggu;
  document.getElementById('s-dipanggil').textContent = data.stat.dipanggil;
  document.getElementById('s-terlewat').textContent  = data.stat.terlewat;
  document.getElementById('s-selesai').textContent   = data.stat.selesai ?? 0;

  const cur = data.current;
  if (cur) {
    document.getElementById('c-num').textContent   = String(cur.nomor_urut).padStart(3,'0');
    document.getElementById('c-name').textContent  = cur.nama;
    document.getElementById('c-loket').textContent = cur.loket ?? '—';
    document.getElementById('c-empty').style.display  = 'none';
    document.getElementById('c-detail').style.display = 'block';
  } else {
    document.getElementById('c-num').textContent = '—';
    document.getElementById('c-empty').style.display  = 'block';
    document.getElementById('c-detail').style.display = 'none';
  }

  document.getElementById('btn-panggil').disabled = data.stat.menunggu === 0;

  const semua = data.semua ?? [];
  document.getElementById('count-badge').textContent = semua.length + ' antrian';

  const tbody = document.getElementById('tabel-antrian');
  if (semua.length === 0) {
    tbody.innerHTML = '<tr class="empty-row"><td colspan="6">Belum ada antrian hari ini</td></tr>';
    return;
  }

  tbody.innerHTML = semua.map(e => {
    const num   = String(e.nomor_urut).padStart(3, '0');
    const jam   = e.created_at ? new Date(e.created_at).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'}) : '—';
    const badge = { menunggu:'badge-menunggu', dipanggil:'badge-dipanggil', terlewat:'badge-terlewat', selesai:'badge-selesai' };
    const label = { menunggu:'Menunggu', dipanggil:'Dipanggil', terlewat:'Terlewat', selesai:'Selesai' };

    let aksi = `<button class="act-btn act-panggil" onclick="panggilItem(${e.id})" title="Panggil">📢</button>`;
    if (e.status === 'dipanggil') {
      aksi += `<button class="act-btn act-selesai"  onclick="selesaikan(${e.id})"   title="Selesai">✅</button>`;
      aksi += `<button class="act-btn act-terlewat" onclick="tandaiTerlewat(${e.id})" title="Terlewat">⏰</button>`;
    }
    if (e.status === 'terlewat') {
      aksi = `<button class="act-btn act-panggil" onclick="panggilItem(${e.id})" title="Panggil Ulang">↩</button>`;
    }
    if (e.status === 'selesai') aksi = '—';

    return `<tr>
      <td class="td-num">${num}</td>
      <td>${escHtml(e.nama)}</td>
      <td>${escHtml(e.loket ?? '—')}</td>
      <td>${jam}</td>
      <td><span class="badge ${badge[e.status] ?? ''}">${label[e.status] ?? e.status}</span></td>
      <td>${aksi}</td>
    </tr>`;
  }).join('');
}

async function panggilBerikutnya() {
  const loket = document.getElementById('sel-loket').value;
  const btn = document.getElementById('btn-panggil');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner"></span> Memanggil…';
  try {
    const res = await fetch('/antrian/panggil', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify({ loket }),
    });
    const json = await res.json().catch(() => ({}));
    if (!res.ok) {
      toast('⚠️ ' + (json.message ?? 'Tidak ada antrian di loket ini'));
    } else {
      toast('✅ Pasien berhasil dipanggil');
      fetchData();
    }
  } catch (err) {
    toast('❌ Gagal terhubung ke server');
  } finally {
    btn.disabled = false;
    btn.innerHTML = '📢 Panggil Berikutnya';
  }
}

async function panggilItem(id) {
  const loket = document.getElementById('sel-loket').value;
  try {
    const res = await fetch(`/antrian/${id}/ulang`, {
      method:'PATCH', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf},
      body: JSON.stringify({ loket }),
    });
    if (res.ok) { toast('✅ Dipanggil'); fetchData(); } else toast('❌ Gagal');
  } catch { toast('❌ Error'); }
}

async function selesaikan(id) {
  try {
    const res = await fetch(`/antrian/${id}/selesai`, {
      method:'PATCH', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf},
    });
    if (res.ok) { toast('✅ Ditandai selesai'); fetchData(); } else toast('❌ Gagal');
  } catch { toast('❌ Error'); }
}

async function tandaiTerlewat(id) {
  try {
    const res = await fetch(`/antrian/${id}/terlewat`, {
      method:'PATCH', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf},
    });
    if (res.ok) { toast('⚠️ Ditandai terlewat'); fetchData(); } else toast('❌ Gagal');
  } catch { toast('❌ Error'); }
}

function toast(msg) {
  const el = document.getElementById('toast');
  el.textContent = msg; el.classList.add('show');
  setTimeout(() => el.classList.remove('show'), 2800);
}
function escHtml(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

connectSSE();
</script>
</body>
</html>