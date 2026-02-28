<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class UndanganController extends Controller
{
    class UndanganController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function download() {
        $data = [
            'nama' => Auth::user()->name,
            'nomor' => '001/RSHP-UNAIR/2026'
        ];
        
        $pdf = Pdf::loadView('undangan.pdf', $data)->setPaper('a4', 'portrait');
        return $pdf->download('Undangan_Kegiatan.pdf');
    }
}
}
