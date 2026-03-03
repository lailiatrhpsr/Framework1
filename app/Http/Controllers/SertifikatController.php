<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SertifikatController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('sertifikat.index');
    }

    public function download($jenis) {
        $data = [
            'nama' => Auth::user()->name,
            'judul' => ucfirst($jenis),
            'jenis' => $jenis,
        ];
        
        $templates = [
            'kelulusan' => 'sertifikat.kelulusan',
            'partisipasi' => 'sertifikat.partisipasi',
            'teladan' => 'sertifikat.teladan',
        ];

        if (!array_key_exists($jenis, $templates)) {
            abort(404, 'Template sertifikat tidak ditemukan');
        }

        $pdf = Pdf::loadView($templates[$jenis], $data)->setPaper('a4', 'landscape');
        return $pdf->download("sertifikat_{$jenis}.pdf");
    }
}