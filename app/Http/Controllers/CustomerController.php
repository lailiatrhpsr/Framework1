<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    // 1. Tampilkan Semua Data Customer
    public function index()
    {
        $customers = DB::table('customer')->get();
        
        return view('admin.customer.index', compact('customers'));
    }

    // 2. Form Tambah Customer 1 (BLOB)
    public function createBlob()
    {
        return view('admin.customer.create_blob');
    }

    public function storeBlob(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'image_camera' => 'required' 
        ]);

        $imgData = $request->input('image_camera');
        $imgData = str_replace('data:image/png;base64,', '', $imgData);
        $imgData = str_replace(' ', '+', $imgData);
        
        $blobData = base64_decode($imgData); 

        DB::table('customer')->insert([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kodepos' => $request->kodepos,
            'foto' => bin2hex($blobData),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('customer.data.index')->with('success', 'Customer versi BLOB berhasil disimpan!');
    }

    // 3. Form Tambah Customer 2 (File Path)
    public function createPath()
    {
        return view('admin.customer.create_path');
    }

    public function storePath(Request $request)
    {
        $imgData = $request->input('image_camera');
        $imgData = str_replace('data:image/png;base64,', '', $imgData);
        $imgData = str_replace(' ', '+', $imgData);
        $fileBinary = base64_decode($imgData);

        $fileName = 'customer_' . Str::random(10) . '_' . time() . '.png';
        \Illuminate\Support\Facades\Storage::disk('public')->put('customers/' . $fileName, $fileBinary);
        
        $filePath = 'storage/customers/' . $fileName;

        \Illuminate\Support\Facades\DB::table('customer')->insert([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kodepos' => $request->kodepos,
            'foto' => $filePath, 
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('customer.data.index')->with('success', 'Customer File Path Berhasil Disimpan!');
    }
}