@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Dashboard Admin</h2>
    <p class="text-muted">Pilih vendor untuk masuk ke dashboard vendor:</p>

    <div class="row">
        @foreach($vendors as $vendor)
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $vendor->nama_vendor }}</h5>
                        <p class="card-text">
                            <strong>ID:</strong> {{ $vendor->id_vendor }} <br>
                            <strong>Email:</strong> {{ $vendor->email ?? '-' }}
                        </p>
                        <a href="{{ route('vendor.vendordashboard', $vendor->id_vendor) }}" 
                           class="btn btn-primary w-100">
                            Masuk Dashboard Vendor
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Dashboard Admin</h2>
    <p class="text-muted">Pilih vendor untuk masuk ke dashboard vendor:</p>

    <div class="row">
        @foreach($vendors as $vendor)
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $vendor->nama_vendor }}</h5>
                        <p class="card-text">
                            <strong>ID:</strong> {{ $vendor->id_vendor }} <br>
                            <strong>Email:</strong> {{ $vendor->email ?? '-' }}
                        </p>
                        <a href="{{ route('vendor.vendordashboard', $vendor->id_vendor) }}" 
                           class="btn btn-primary w-100">
                            Masuk Dashboard Vendor
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
