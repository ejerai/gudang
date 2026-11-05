<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MutasiStokController extends Controller
{
    public function index()
    {
        $mutasi = MutasiStok::with(['barang', 'user'])->latest()->paginate(10);

        return view('mutasi.index', compact('mutasi'));
    }

    public function create()
    {
        $barang = Barang::orderBy('nama_barang')->get(['id', 'kode_barang', 'nama_barang', 'stok']);

        return view('mutasi.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'barang_id'  => ['required', Rule::exists('barang', 'id')->whereNull('deleted_at')],
            'jenis'      => ['required', 'in:masuk,keluar'],
            'jumlah'     => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ], [
            'required' => ':attribute wajib diisi.',
            'integer'  => ':attribute harus berupa bilangan bulat.',
            'min'      => ':attribute minimal :min.',
            'in'       => ':attribute tidak valid.',
            'exists'   => ':attribute tidak valid.',
        ], [
            'barang_id' => 'Barang',
            'jenis'     => 'Jenis mutasi',
            'jumlah'    => 'Jumlah',
        ]);

        DB::transaction(function () use ($data, $request) {
            $barang = Barang::lockForUpdate()->findOrFail($data['barang_id']);

            if ($data['jenis'] === 'keluar' && $data['jumlah'] > $barang->stok) {
                throw ValidationException::withMessages([
                    'jumlah' => "Stok tidak mencukupi. Stok tersedia: {$barang->stok}.",
                ]);
            }

            $barang->stok += $data['jenis'] === 'masuk' ? $data['jumlah'] : -$data['jumlah'];
            $barang->save();

            MutasiStok::create([
                'barang_id'  => $barang->id,
                'user_id'    => $request->user()->id,
                'jenis'      => $data['jenis'],
                'jumlah'     => $data['jumlah'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);
        });

        return redirect()->route('admin.mutasi.index')->with('success', 'Mutasi stok berhasil dicatat.');
    }
}
