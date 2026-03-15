@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <h2 class="mb-4 text-primary">Pilih Wilayah Administrasi</h2>

  <div class="mb-3">
    <label for="provinsi" class="form-label fw-bold">Provinsi</label>
    <select id="provinsi" class="form-select">
      <option value="">Pilih Provinsi</option>
    </select>
  </div>

  <div class="mb-3">
    <label for="kota" class="form-label fw-bold">Kota</label>
    <select id="kota" class="form-select">
      <option value="">Pilih Kota</option>
    </select>
  </div>

  <div class="mb-3">
    <label for="kecamatan" class="form-label fw-bold">Kecamatan</label>
    <select id="kecamatan" class="form-select">
      <option value="">Pilih Kecamatan</option>
    </select>
  </div>

  <div class="mb-3">
    <label for="kelurahan" class="form-label fw-bold">Kelurahan</label>
    <select id="kelurahan" class="form-select">
      <option value="">Pilih Kelurahan</option>
    </select>
  </div>
</div>
<div class="mt-4">
  <h5>Hasil Pilihan:</h5>
  <p id="hasil">Belum ada pilihan</p>
</div>


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Provinsi
  axios.get('/provinsi')
    .then(res => {
      const provSelect = document.getElementById('provinsi');
      provSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
      res.data.forEach(item => {
        provSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
      });
    })
    .catch(() => Swal.fire('Error!', 'Gagal memuat provinsi', 'error'));

  // Kota/Kabupaten
  document.getElementById('provinsi').addEventListener('change', function() {
    const provId = this.value;
    const kotaSelect = document.getElementById('kota');
    const kecSelect = document.getElementById('kecamatan');
    const kelSelect = document.getElementById('kelurahan');

    kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
    kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
    kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';

    if (!provId) return;

    axios.get(`/kota/${provId}`)
      .then(res => {
        res.data.forEach(item => {
          kotaSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });
      })
      .catch(() => Swal.fire('Error!', 'Gagal memuat kota', 'error'));
  });

  // Kecamatan
  document.getElementById('kota').addEventListener('change', function() {
    const kotaId = this.value;
    const kecSelect = document.getElementById('kecamatan');
    const kelSelect = document.getElementById('kelurahan');

    kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
    kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';

    if (!kotaId) return;

    axios.get(`/kecamatan/${kotaId}`)
      .then(res => {
        res.data.forEach(item => {
          kecSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });
      })
      .catch(() => Swal.fire('Error!', 'Gagal memuat kecamatan', 'error'));
  });

  // Kelurahan
  document.getElementById('kecamatan').addEventListener('change', function() {
    const kecId = this.value;
    const kelSelect = document.getElementById('kelurahan');

    kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';

    if (!kecId) return;

    axios.get(`/kelurahan/${kecId}`)
      .then(res => {
        res.data.forEach(item => {
          kelSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });
      })
      .catch(() => Swal.fire('Error!', 'Gagal memuat kelurahan', 'error'));
  });

  document.getElementById('kelurahan').addEventListener('change', function() {
    const prov = document.getElementById('provinsi');
    const kota = document.getElementById('kota');
    const kec = document.getElementById('kecamatan');
    const kel = document.getElementById('kelurahan');

    const hasil = document.getElementById('hasil');
    hasil.innerHTML = `
        Anda memilih: <br>
        Provinsi: ${prov.options[prov.selectedIndex].text} <br>
        Kota: ${kota.options[kota.selectedIndex].text} <br>
        Kecamatan: ${kec.options[kec.selectedIndex].text} <br>
        Kelurahan: ${kel.options[kel.selectedIndex].text}
    `;

    // 🔄 Reset semua dropdown ke kondisi awal
    prov.innerHTML = '<option value="">Pilih Provinsi</option>';
    kota.innerHTML = '<option value="">Pilih Kota</option>';
    kec.innerHTML = '<option value="">Pilih Kecamatan</option>';
    kel.innerHTML = '<option value="">Pilih Kelurahan</option>';

    // Load ulang provinsi dari API
    axios.get('/provinsi')
      .then(res => {
        res.data.forEach(item => {
          prov.innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });
      })
      .catch(() => Swal.fire('Error!', 'Gagal memuat provinsi', 'error'));
  });

</script>
@endsection
