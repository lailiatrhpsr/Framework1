@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Point Of Sales (POS)</h2>

    <div class="card card-body mb-4 shadow-sm">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Kode Barang (Enter):</label>
                <input type="text" id="kode_input" class="form-control" placeholder="Contoh: BRG001" autofocus>
            </div>
            <div class="col-md-3">
                <label class="form-label">Nama Barang:</label>
                <input type="text" id="nama_view" class="form-control" readonly>
                <input type="hidden" id="id_barang_hidden">
            </div>
            <div class="col-md-2">
                <label class="form-label">Harga:</label>
                <input type="text" id="harga_view" class="form-control" readonly>
            </div>
            <div class="col-md-2">
                <label class="form-label">Jumlah:</label>
                <input type="number" id="qty_input" class="form-control" value="1" min="1">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button id="btn_tambah" class="btn btn-primary d-block w-100" disabled>
                    Tambahkan
                </button>
            </div>
        </div>
    </div>

    <div class="card card-body shadow-sm">
        <table class="table table-striped" id="tabel_keranjang">
            <thead class="table-dark">
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                </tbody>
            <tfoot>
                <tr class="table-secondary">
                    <th colspan="4" class="text-end">Total Bayar:</th>
                    <th id="total_bayar">Rp 0</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        <div class="text-end mt-3">
            <button id="btn_proses_bayar" class="btn btn-success btn-lg">
                Bayar
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    let keranjang = [];

    // 1. AJAX JQuery: Cek Barang saat Enter
    $('#kode_input').on('keypress', function(e) {
        if (e.which == 13) { 
            let kode = $(this).val();
            let btnTambah = $('#btn_tambah');
            btnTambah.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Mencari...');

            $.ajax({
                url: '/pos/cek-barang/' + kode,
                method: 'GET',
                success: function(response) {
                    $('#id_barang_hidden').val(response.data.id);
                    $('#nama_view').val(response.data.nama);
                    $('#harga_view').val(response.data.harga);
                    $('#qty_input').val(1);
                    
                    btnTambah.prop('disabled', false).html('Tambahkan');
                    $('#qty_input').focus();
                },
                error: function() {
                    Swal.fire('Error!', 'Barang tidak ditemukan.', 'error'); 
                    resetInput();
                    btnTambah.html('Tambahkan');
                }
            });
        }
    });

    // 2. Tambah Barang ke Tabel 
    $('#btn_tambah').on('click', function() {
        let id = $('#id_barang_hidden').val();
        let kode = $('#kode_input').val();
        let nama = $('#nama_view').val();
        let harga = parseFloat($('#harga_view').val());
        let qty = parseInt($('#qty_input').val());

        if (qty <= 0) return;

        let index = keranjang.findIndex(item => item.kode === kode);
        if (index > -1) {
            keranjang[index].qty += qty;
            keranjang[index].subtotal = keranjang[index].qty * harga;
        } else {
            keranjang.push({
                id_barang: id,
                kode: kode,
                nama: nama,
                harga: harga,
                qty: qty,
                subtotal: harga * qty
            });
        }

        renderTable();
        resetInput();
        $('#kode_input').focus();
    });

    // 3. Axios: Simpan Transaksi (Konsep Promise) [cite: 255, 381]
    $('#btn_proses_bayar').on('click', function() {
        if (keranjang.length === 0) {
            Swal.fire('Peringatan', 'Keranjang masih kosong!', 'warning');
            return;
        }

        let btnBayar = $(this);
        let originalText = btnBayar.html();

        btnBayar.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Memproses...');

        axios.post("{{ route('pos.simpan') }}", {
            _token: "{{ csrf_token() }}", 
            total: hitungTotal(),
            items: keranjang
        })
        .then(function (response) {
            Swal.fire('Sukses!', 'Pembayaran transaksi berhasil disimpan.', 'success');
            
            keranjang = [];
            renderTable();
            btnBayar.prop('disabled', false).html(originalText);
        })
        .catch(function (error) {
            Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan.', 'error');
            btnBayar.prop('disabled', false).html(originalText);
        });
    });

    function renderTable() {
        let html = '';
        keranjang.forEach((item, index) => {
            html += `
                <tr>
                    <td>${item.kode}</td>
                    <td>${item.nama}</td>
                    <td>Rp ${item.harga.toLocaleString()}</td>
                    <td>${item.qty}</td>
                    <td>Rp ${item.subtotal.toLocaleString()}</td>
                    <td><button class="btn btn-danger btn-sm" onclick="hapusItem(${index})">Hapus</button></td>
                </tr>
            `;
        });
        $('#tabel_keranjang tbody').html(html);
        $('#total_bayar').text('Rp ' + hitungTotal().toLocaleString()); 
    }

    function hitungTotal() {
        return keranjang.reduce((sum, item) => sum + item.subtotal, 0);
    }

    function resetInput() {
        $('#kode_input').val('');
        $('#id_barang_hidden').val('');
        $('#nama_view').val('');
        $('#harga_view').val('');
        $('#qty_input').val(1);
        $('#btn_tambah').prop('disabled', true).html('Tambahkan');
    }

    window.hapusItem = function(index) {
        keranjang.splice(index, 1);
        renderTable();
    };
});
</script>
@endpush