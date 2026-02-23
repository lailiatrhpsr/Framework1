<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku; 
use App\Models\Kategori;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buku = Buku::with('kategori')->get(); 
        $kategori = Kategori::all(); 
        return view('admin.buku', compact('buku', 'kategori'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([ 
            'kode' => 'required|unique:buku,kode', 
            'judul' => 'required', 
            'pengarang' => 'required', 
            'idkategori' => 'required' ]); 
        Buku::create($request->only(['idkategori','kode','judul','pengarang'])); 
        return redirect()->back()->with('success', 'Buku berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
