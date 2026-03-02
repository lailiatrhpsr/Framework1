<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all(); 
        return view('admin.barang.index', compact('barang'));
    }

    public function create()
    {
        return view('admin.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([ 
            'nama_barang' => 'required', 
            'harga' => 'required|numeric', 
            'stok' => 'required|integer', ]); 

        Barang::create($request->all()); 
        return redirect()->route('barang.index')->with('success','Barang berhasil ditambahkan'); 
    }

    public function edit(string $id)
    {
        $barang = Barang::findOrFail($id); 
        
        return view('admin.barang.edit', compact('barang'));
    }

    public function update(Request $request, string $id)
    {
        $barang = Barang::findOrFail($id); 
        $barang->update($request->all()); 
        
        return redirect()->route('barang.index')->with('success','Barang berhasil diupdate');
    }

    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id); 
        $barang->delete(); 
        
        return redirect()->route('barang.index')->with('success','Barang berhasil dihapus');
    }

    public function labelIndex()
    {
        $barang = Barang::all();
        return view('admin.barang.label_index', compact('barang'));
    }

    public function cetakLabel(Request $request)
    {
        $barangIds = $request->input('barang', []);
        if (empty($barangIds)) {
            return redirect()->back()->with('error', 'Pilih minimal satu barang.');
        }

        $x = (int)$request->input('x', 1); 
        $y = (int)$request->input('y', 1); 

        $barangTerpilih = Barang::whereIn('id_barang', $barangIds)->get();

        $barang = [];
        foreach ($barangTerpilih as $b) {
            for ($i = 0; $i < $b->stok; $i++) {
                $barang[] = $b;
            }
        }

        $pdf = \PDF::loadView('admin.barang.label_barang', compact('barang', 'x', 'y'))
            ->setPaper([0, 0, 323.15, 510.24], 'landscape');
        return $pdf->stream('label-barang.pdf');
    }
}
