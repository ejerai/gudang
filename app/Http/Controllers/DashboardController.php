<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiStok;
use App\Models\Supplier;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalSupplier'    => Supplier::count(),
            'totalJenisBarang' => Barang::count(),
            'totalStok'        => (int) Barang::sum('stok'),
            'mutasiTerakhir'   => MutasiStok::with(['barang', 'user'])->latest()->limit(5)->get(),
        ]);
    }
}
