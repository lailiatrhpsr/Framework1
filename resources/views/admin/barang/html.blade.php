@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Tambah Barang</h2>
  <div class="row mb-4">
    <div class="col-md-6">
      <a href="{{ route('barang.datatables') }}" class="btn btn-primary w-100">
        Buka Versi Datatables
      </a>
    </div>
  </div>
  <form id="barangForm">
    <input type="text" id="nama" placeholder="Nama barang" required>
    <input type="number" id="harga" placeholder="Harga barang" required>
    <button id="submitBtn" type="submit" class="btn btn-success">
      <span id="btnText">Simpan</span>
    </button>
  </form>

  <hr>

  <h2>Daftar Barang (HTML Table)</h2>
  <table id="barangTable" class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nama Barang</th>
        <th>Harga</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>

<!-- Modal untuk Edit/Hapus -->
<div class="modal fade" id="barangModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="modalForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" id="modalId" readonly class="form-control mb-2">
          <input type="text" id="modalNama" required class="form-control mb-2" placeholder="Nama barang">
          <input type="number" id="modalHarga" required class="form-control mb-2" placeholder="Harga barang">
        </div>
        <div class="modal-footer">
          <button type="button" id="hapusBtn" class="btn btn-danger">Hapus</button>
          <button type="submit" id="ubahBtn" class="btn btn-primary">Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll("form").forEach(function(form) {
  form.addEventListener("submit", function(e) {
    e.preventDefault();
    const btn = form.querySelector("button[type='submit']");
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Loading...';
    btn.disabled = true;

    const nama = document.getElementById("nama").value;
    const harga = document.getElementById("harga").value;
    const id = Date.now();

    const tableBody = document.querySelector("#barangTable tbody");
    const newRow = tableBody.insertRow();
    newRow.innerHTML = `<td>${id}</td><td>${nama}</td><td>${harga}</td>`;
    newRow.style.cursor = "pointer";

    // Klik row → buka modal
    newRow.addEventListener("click", function() {
      const cells = newRow.querySelectorAll("td");
      document.getElementById("modalId").value = cells[0].innerText;
      document.getElementById("modalNama").value = cells[1].innerText;
      document.getElementById("modalHarga").value = cells[2].innerText;
      document.getElementById("modalForm").dataset.rowIndex = newRow.rowIndex;
      new bootstrap.Modal(document.getElementById("barangModal")).show();
    });

    document.getElementById("nama").value = "";
    document.getElementById("harga").value = "";

    btn.innerHTML = '<span id="btnText">Simpan</span>';
    btn.disabled = false;
  });
});

// Hapus row
document.getElementById("hapusBtn").addEventListener("click", function() {
  const idx = document.getElementById("modalForm").dataset.rowIndex;
  document.getElementById("barangTable").deleteRow(idx);
  bootstrap.Modal.getInstance(document.getElementById("barangModal")).hide();
});

// Ubah row
document.getElementById("modalForm").addEventListener("submit", function(e) {
  e.preventDefault();
  const idx = this.dataset.rowIndex;
  const row = document.getElementById("barangTable").rows[idx];
  const btn = document.getElementById("ubahBtn");

  if (!this.checkValidity()) {
    this.reportValidity();
    return;
  }

  // Spinner di tombol Ubah
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Updating...';
  btn.disabled = true;

  row.cells[1].innerText = document.getElementById("modalNama").value;
  row.cells[2].innerText = document.getElementById("modalHarga").value;

  bootstrap.Modal.getInstance(document.getElementById("barangModal")).hide();

  btn.innerHTML = 'Ubah';
  btn.disabled = false;
});
</script>
@endpush
