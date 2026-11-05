<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBarangRequest;
use App\Http\Requests\UpdateBarangRequest;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $kategoriId = $request->query('kategori_id');

        $barang = Barang::with(['supplier', 'kategori']) // eager loading: mencegah N+1
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($w) use ($search) {
                    $w->where('nama_barang', 'like', "%{$search}%")
                      ->orWhere('kode_barang', 'like', "%{$search}%");
                });
            })
            ->when($kategoriId, fn ($query) => $query->where('kategori_id', $kategoriId))
            ->orderBy('nama_barang')
            ->paginate(10)
            ->withQueryString();

        $kategori = KategoriBarang::orderBy('nama_kategori')->get();

        return view('barang.index', compact('barang', 'kategori', 'search', 'kategoriId'));
    }

    // Pencarian cepat (JSON): dipakai pemilih barang di form mutasi stok
    public function cari(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $hasil = Barang::with('kategori')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('nama_barang', 'like', "%{$q}%")
                      ->orWhere('kode_barang', 'like', "%{$q}%");
                });
            })
            ->orderBy('nama_barang')
            ->limit(8)
            ->get()
            ->map(fn (Barang $b) => [
                'id'       => $b->id,
                'kode'     => $b->kode_barang,
                'nama'     => $b->nama_barang,
                'stok'     => $b->stok,
                'kategori' => $b->kategori?->nama_kategori,
            ]);

        return response()->json($hasil);
    }

    public function create()
    {
        return view('barang.create', [
            'kategori'  => KategoriBarang::orderBy('nama_kategori')->get(),
            'suppliers' => Supplier::orderBy('nama_pt')->get(),
        ]);
    }

    public function store(StoreBarangRequest $request)
    {
        Barang::create($request->validated());

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', [
            'barang'    => $barang,
            'kategori'  => KategoriBarang::orderBy('nama_kategori')->get(),
            'suppliers' => Supplier::orderBy('nama_pt')->get(),
        ]);
    }

    public function update(UpdateBarangRequest $request, Barang $barang)
    {
        $barang->update($request->validated());

        return redirect()->route('admin.barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete(); // soft delete

        return redirect()->route('admin.barang.index')->with('success', 'Barang dipindahkan ke data terhapus.');
    }

    // Data yang terhapus sementara
    public function trash()
    {
        $barang = Barang::onlyTrashed()
            ->with(['supplier', 'kategori'])
            ->latest('deleted_at')
            ->paginate(10);

        return view('barang.trash', compact('barang'));
    }

    public function restore($id)
    {
        Barang::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('admin.barang.trash')->with('success', 'Barang berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        Barang::onlyTrashed()->findOrFail($id)->forceDelete();

        return redirect()->route('admin.barang.trash')->with('success', 'Barang dihapus permanen.');
    }
}
