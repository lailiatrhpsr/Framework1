@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Pilih Undangan</h3>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <h4>Undangan Verifikasi Sistem</h4>
                    <a href="{{ route('undangan.download', 'verifikasi') }}" class="btn btn-light btn-sm">Download PDF</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <h4>Undangan Rapat</h4>
                    <a href="{{ route('undangan.download', 'rapat') }}" class="btn btn-light btn-sm">Download PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
