@extends('layouts.customer')

@section('title', 'Selesaikan Pembayaran')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                
                <div class="p-4 text-center" style="background-color: #f3e8ff;">
                    <i class="bi bi-wallet2 fs-1 text-purple mb-2"></i>
                    <h4 class="fw-bold mb-1" style="color: #1e293b;">Menunggu Pembayaran</h4>
                    <p class="text-muted small mb-0">Pesanan Anda telah berhasil dibuat.</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">ID Pesanan</span>
                        <span class="fw-bold fs-6">#{{ $pesanan->id_pesanan }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <span class="text-muted">Metode Pilihan</span>
                        <span class="badge badge-purple-soft rounded-pill px-3 py-2 border border-purple">
                            <i class="bi bi-credit-card-2-front me-1"></i> {{ $pesanan->metode_bayar }}
                        </span>
                    </div>

                    <div class="text-center mb-5 p-4 rounded-4 bg-light">
                        <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">TOTAL YANG HARUS DIBAYAR</small>
                        <h1 class="display-6 fw-extrabold text-purple mb-0">Rp {{ number_format($pesanan->total,0,',','.') }}</h1>
                    </div>

                    <div class="d-grid mb-3">
                        <button id="pay-button" class="btn btn-purple btn-lg rounded-pill fw-bold shadow-sm py-3 hover-push">
                            Bayar Sekarang <i class="bi bi-lock-fill ms-2"></i>
                        </button>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('customer.cart') }}" class="btn btn-link text-danger small text-decoration-none" onclick="return confirm('Anda yakin ingin membatalkan pembayaran ini?')">
                            <i class="bi bi-x-circle me-1"></i> Batalkan & Kembali ke Keranjang
                        </button>
                    </div>

                </div>
            </div>

            <div class="mt-4 p-3 bg-white rounded-4 border border-light shadow-sm d-flex align-items-center justify-content-center text-center">
                <i class="bi bi-shield-lock-fill text-success fs-4 me-3"></i>
                <small class="text-muted">Pembayaran diproses secara aman menggunakan teknologi enkripsi Midtrans Snap.</small>
            </div>
            
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $snapToken }}', {
            // Jika berhasil, lari ke halaman Detail Pesanan
            onSuccess: function(result){ 
                window.location.href = "{{ route('customer.pesanan.show', $pesanan->id_pesanan) }}"; 
            },
            
            // Jika pending (user pilih metode tapi belum tf)
            onPending: function(result){ 
                console.log(result); 
                alert('Menunggu pembayaran Anda. Detail metode pembayaran akan dikirim via email/snap popup.');
            },
            
            // Jika error
            onError: function(result){ 
                console.log(result); 
                alert('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi nanti.');
            },
            
            // Jika popup ditutup tanpa bayar
            onClose: function(){ 
                // Tidak perlu alert, cukup biarkan user di halaman ini agar mereka bisa klik bayar lagi.
            }
        });
    };
</script>

<style>
    /* Styling Tambahan */
    .text-purple { color: #a855f7 !important; }
    .btn-purple { background-color: #a855f7; color: white; border: none; }
    .btn-purple:hover { background-color: #9333ea; color: white; transform: translateY(-2px); transition: 0.3s; }
    .badge-purple-soft { background-color: #f3e8ff; color: #a855f7; font-weight: 600; }
    .border-purple { border: 1px solid #a855f7 !important; }
    .fw-extrabold { font-weight: 800; }
    .hover-push:active { transform: scale(0.97); transition: 0.1s; }
    
    /* Memperhalus tampilan modal Midtrans agar menyatu dengan desain */
    iframe#snap-midtrans {
        border-radius: 20px !important;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2) !important;
    }
</style>
@endsection