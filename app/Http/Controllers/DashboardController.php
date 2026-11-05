<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiStok;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Rentang grafik pergerakan stok: 7, 14, atau 30 hari terakhir
        $rentang = (int) $request->query('rentang', 14);
        if (! in_array($rentang, [7, 14, 30], true)) {
            $rentang = 14;
        }

        $mulai = now()->subDays($rentang - 1)->startOfDay();

        $rekap = MutasiStok::query()
            ->where('created_at', '>=', $mulai)
            ->selectRaw('DATE(created_at) as tgl, jenis, SUM(jumlah) as total')
            ->groupBy('tgl', 'jenis')
            ->get()
            ->groupBy('tgl');

        $grafik = [];
        for ($i = 0; $i < $rentang; $i++) {
            $hari = $mulai->copy()->addDays($i);
            $baris = $rekap->get($hari->format('Y-m-d'), collect());
            $grafik[] = [
                'label'  => $hari->format('j M'),
                'masuk'  => (int) ($baris->firstWhere('jenis', 'masuk')->total ?? 0),
                'keluar' => (int) ($baris->firstWhere('jenis', 'keluar')->total ?? 0),
            ];
        }
        $puncak = max(1, collect($grafik)->flatMap(fn ($g) => [$g['masuk'], $g['keluar']])->max());

        return view('dashboard', [
            'totalSupplier'    => Supplier::count(),
            'totalJenisBarang' => Barang::count(),
            'totalStok'        => (int) Barang::sum('stok'),
            'mutasiTerakhir'   => MutasiStok::with(['barang', 'user'])->latest()->limit(5)->get(),
            'rentang'          => $rentang,
            'grafik'           => $grafik,
            'puncak'           => $puncak,
        ]);
    }
}
