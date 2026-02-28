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

    public function download(Request $request) {
        $data = [
            'nama' => Auth::user()->name,
            'judul' => $request->query('judul', 'Sertifikat Partisipasi')
        ];
        
        $pdf = Pdf::loadView('sertifikat.pdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('Sertifikat.pdf');
    }
}