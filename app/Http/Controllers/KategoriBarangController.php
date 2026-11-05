<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $kategori = KategoriBarang::withCount('barang')->orderBy('kode_kategori')->get();

        return view('kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_kategori' => ['required', 'string', 'max:20', 'unique:kategori_barang,kode_kategori'],
            'nama_kategori' => ['required', 'string', 'max:100'],
        ], [
            'required' => ':attribute wajib diisi.',
            'max'      => ':attribute maksimal :max karakter.',
            'unique'   => ':attribute sudah digunakan.',
        ]);

        KategoriBarang::create($data);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }
}
