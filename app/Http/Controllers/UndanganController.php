<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class UndanganController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() { 
         return view('undangan.index'); }

    public function download($jenis) { 
        $data = [ 
            'nama' => Auth::user()->name, 
            'nomor' => '001/RSHP/III/2026',
            'tanggal' => '10 Maret 2026', 
            'waktu' => '09.00 WIB', 
            'tempat' => 'Ruang Rapat Toko Buku Husada Utama Raya', 
            'jenis' => $jenis,]; 
        
        $templates = [ 
            'verifikasi' => 'undangan.verifikasi', 
            'rapat' => 'undangan.rapat', ]; 
         
        if (!array_key_exists($jenis, $templates)) { 
            abort(404, 'Template undangan tidak ditemukan'); } 

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($templates[$jenis], 
        $data)->setPaper('a4', 'portrait'); return $pdf->download("undangan_{$jenis}.pdf"); }
}
