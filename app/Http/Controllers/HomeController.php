<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Http\Request;
use App\Models\Buku; 
use App\Models\Kategori;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function cetakSertifikat()
    {
        // Mengacu ke folder resources/views/admin/sertifikat.blade.php
        $pdf = Pdf::loadView('admin.sertifikat')->setPaper('a4', 'landscape'); 
        return $pdf->download('Sertifikat_Admin.pdf');
    }

    public function cetakUndangan()
    {
        // Mengacu ke folder resources/views/admin/undangan.blade.php
        $pdf = Pdf::loadView('admin.undangan')->setPaper('a4', 'portrait');
        return $pdf->download('Undangan_Admin.pdf');
    }
}
