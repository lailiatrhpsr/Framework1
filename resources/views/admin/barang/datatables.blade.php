@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Tambah Barang</h2>
  <form id="barangForm">
    <input type="text" id="nama" placeholder="Nama barang" required>
    <input type="number" id="harga" placeholder="Harga barang" required>
    <button id="submitBtn" type="submit" class="btn btn-success">
      <span id="btnText">Simpan</span>
    </button>
  </form>

  <hr>

  <h2>Daftar Barang (DataTables)</h2>
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
  const table = $('#barangTable').DataTable();

  $("#barangForm").on("submit", function(e) {
    e.preventDefault();
    const btn = $("#submitBtn");
    if (!this.checkValidity()) {
      this.reportValidity();
      return;
    }
    btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Loading...');
    btn.prop("disabled", true);

    const nama = $("#nama").val();
    const harga = $("#harga").val();
    const id = Date.now();

    table.row.add([id, nama, harga]).draw();

    $("#nama").val("");
    $("#harga").val("");

    btn.html('<span id="btnText">Simpan</span>');
    btn.prop("disabled", false);
  });

  // Hover row jadi pointer
  $('#barangTable tbody').on('mouseenter', 'tr', function() {
    $(this).css('cursor', 'pointer');
  });

  // Klik row 
  $('#barangTable tbody').on('click', 'tr', function() {
    const data = table.row(this).data();
    $('#modalId').val(data[0]);
    $('#modalNama').val(data[1]);
    $('#modalHarga').val(data[2]);
    $('#modalForm').data('row', this);
    $('#barangModal').modal('show');
  });

  // Hapus row
  $('#hapusBtn').on('click', function() {
    const row = $('#modalForm').data('row');
    table.row(row).remove().draw();
    $('#barangModal').modal('hide');
  });

  // Ubah row
  $('#modalForm').on('submit', function(e) {
    e.preventDefault();
    const row = $('#modalForm').data('row');
    const btn = $('#ubahBtn');
    if (!this.checkValidity()) {
      this.reportValidity();
      return;
    }
    // Spinner di tombol Ubah
    btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Updating...');
    btn.prop("disabled", true);

    table.row(row).data([
      $('#modalId').val(),
      $('#modalNama').val(),
      $('#modalHarga').val()
    ]).draw();

    $('#barangModal').modal('hide');
    btn.html('Ubah');
    btn.prop("disabled", false);
  });
});
</script>
@endpush
