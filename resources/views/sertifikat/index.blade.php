@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title"> Studi Kasus 2: Pilih Sertifikat </h3>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <h4 class="font-weight-normal mb-3">Sertifikat Kelulusan <i class="mdi mdi-school mdi-24px float-right"></i></h4>
                    <p>Format: Landscape (A4)</p>
                    <a href="{{ route('sertifikat.download', ['judul' => 'Sertifikat Kelulusan Proyek']) }}" class="btn btn-light btn-sm">Download PDF</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <h4 class="font-weight-normal mb-3">Sertifikat Partisipasi <i class="mdi mdi-medal mdi-24px float-right"></i></h4>
                    <p>Format: Landscape (A4)</p>
                    <a href="{{ route('sertifikat.download', ['judul' => 'Sertifikat Partisipasi Workshop']) }}" class="btn btn-light btn-sm">Download PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection