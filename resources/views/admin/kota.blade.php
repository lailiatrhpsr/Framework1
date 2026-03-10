@extends('layouts.app')

@section('content')
<div class="container">
  <!-- Card 1: Select biasa -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Select</div>
        <div class="card-body">
          <form id="formSelect">
            <input type="text" id="kotaInput" placeholder="Kota" required>
            <button type="submit" class="btn btn-success">Tambahkan</button>
          </form>

          <hr>

          <label for="kotaSelect">Select Kota:</label>
          <select id="kotaSelect" class="form-select"></select>

          <p class="mt-3">Kota Terpilih: <span id="kotaTerpilih"></span></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 2: Select2 -->
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Select2</div>
        <div class="card-body">
          <form id="formSelect2">
            <input type="text" id="kotaInput2" placeholder="Kota" required>
            <button type="submit" class="btn btn-success">Tambahkan</button>
          </form>

          <hr>

          <label for="kotaSelect2">Select Kota:</label>
          <select id="kotaSelect2" class="form-select"></select>

          <p class="mt-3">Kota Terpilih: <span id="kotaTerpilih2"></span></p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
  // Card 1
  $("#formSelect").on("submit", function(e) {
    e.preventDefault();
    const kota = $("#kotaInput").val();
    if (kota) {
      $("#kotaSelect").append(new Option(kota, kota));
      $("#kotaInput").val("");
    }
  });

  $("#kotaSelect").on("change", function() {
    $("#kotaTerpilih").text($(this).val());
  });

  // Card 2
  $("#kotaSelect2").select2();

  $("#formSelect2").on("submit", function(e) {
    e.preventDefault();
    const kota = $("#kotaInput2").val();
    if (kota) {
      const newOption = new Option(kota, kota, false, false);
      $("#kotaSelect2").append(newOption).trigger('change');
      $("#kotaInput2").val("");
    }
  });

  $("#kotaSelect2").on("change", function() {
    $("#kotaTerpilih2").text($(this).val());
  });
});
</script>
@endpush
