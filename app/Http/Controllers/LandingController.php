<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\MutasiStok;
use App\Models\Supplier;

class LandingController extends Controller
{
    // Halaman depan publik: angka statistik diambil dari data sebenarnya
    public function __invoke()
    {
        try {
            $statistik = [
                'barang'   => Barang::count(),
                'kategori' => KategoriBarang::count(),
                'supplier' => Supplier::count(),
                'mutasi'   => MutasiStok::count(),
            ];
        } catch (\Throwable $e) {
            // Database belum siap (mis. belum migrate): tetap tampilkan halaman
            $statistik = ['barang' => 0, 'kategori' => 0, 'supplier' => 0, 'mutasi' => 0];
        }

        return view('landing.index', compact('statistik'));
    }
}
