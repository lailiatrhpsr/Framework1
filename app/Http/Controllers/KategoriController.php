<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku; 
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index() 
    {
        $kategori = Kategori::all(); 
        return view('admin.kategori', compact('kategori')); 
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([ 'nama_kategori' => 'required|unique:kategori,nama_kategori' ]); 
        Kategori::create([ 'nama_kategori' => $request->nama_kategori ]); 
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!'); 
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
