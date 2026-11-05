<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    // Barang dengan stok di bawah atau sama dengan angka ini dianggap menipis
    public const BATAS_STOK_MENIPIS = 10;

    public function index(Request $request)
    {
        [$dari, $sampai] = $this->periode($request);
        $awal = $dari->copy()->startOfDay();
        $akhir = $sampai->copy()->endOfDay();

        // 1. Nilai persediaan per kategori (stok x harga satuan), barang terhapus tidak dihitung
        $perKategori = KategoriBarang::query()
            ->leftJoin('barang', function ($join) {
                $join->on('barang.kategori_id', '=', 'kategori_barang.id')
                     ->whereNull('barang.deleted_at');
            })
            ->groupBy('kategori_barang.id', 'kategori_barang.nama_kategori')
            ->orderBy('kategori_barang.nama_kategori')
            ->get([
                'kategori_barang.nama_kategori',
                DB::raw('COUNT(barang.id) as jenis'),
                DB::raw('COALESCE(SUM(barang.stok), 0) as total_stok'),
                DB::raw('COALESCE(SUM(barang.stok * barang.harga_satuan), 0) as nilai'),
            ]);

        $totalStok = (int) $perKategori->sum('total_stok');
        $totalNilai = (float) $perKategori->sum('nilai');

        // 2. Barang yang perlu segera diisi ulang
        $stokMenipis = Barang::with(['kategori', 'supplier'])
            ->where('stok', '<=', self::BATAS_STOK_MENIPIS)
            ->orderBy('stok')
            ->orderBy('nama_barang')
            ->get();

        // 3. Rekap mutasi pada periode terpilih
        $mutasiQuery = MutasiStok::query()->whereBetween('created_at', [$awal, $akhir]);
        $totalMasuk = (int) (clone $mutasiQuery)->where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = (int) (clone $mutasiQuery)->where('jenis', 'keluar')->sum('jumlah');

        $mutasi = (clone $mutasiQuery)
            ->with(['barang', 'user'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('laporan.index', [
            'dari'        => $dari,
            'sampai'      => $sampai,
            'perKategori' => $perKategori,
            'totalStok'   => $totalStok,
            'totalNilai'  => $totalNilai,
            'stokMenipis' => $stokMenipis,
            'batas'       => self::BATAS_STOK_MENIPIS,
            'totalMasuk'  => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'mutasi'      => $mutasi,
        ]);
    }

    // Unduh rincian mutasi periode terpilih sebagai CSV (bisa dibuka di Excel)
    public function exportMutasi(Request $request)
    {
        [$dari, $sampai] = $this->periode($request);
        $awal = $dari->copy()->startOfDay();
        $akhir = $sampai->copy()->endOfDay();

        $namaFile = 'laporan-mutasi-' . $dari->format('Ymd') . '-' . $sampai->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($awal, $akhir) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8

            fputcsv($out, ['Waktu', 'Kode barang', 'Nama barang', 'Jenis', 'Jumlah', 'Oleh', 'Keterangan'], ';', '"', '');

            MutasiStok::with(['barang', 'user'])
                ->whereBetween('created_at', [$awal, $akhir])
                ->orderBy('id')
                ->chunk(500, function ($rows) use ($out) {
                    foreach ($rows as $m) {
                        fputcsv($out, [
                            $m->created_at->format('d/m/Y H:i'),
                            $m->barang?->kode_barang,
                            $m->barang?->nama_barang,
                            $m->jenis === 'masuk' ? 'Masuk' : 'Keluar',
                            $m->jumlah,
                            $m->user?->name,
                            $m->keterangan,
                        ], ';', '"', '');
                    }
                });

            fclose($out);
        }, $namaFile, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    // Periode default: awal bulan ini sampai hari ini
    private function periode(Request $request): array
    {
        $request->validate([
            'dari'   => ['nullable', 'date'],
            'sampai' => ['nullable', 'date'],
        ], [
            'date' => 'Format tanggal tidak valid.',
        ]);

        $dari = $request->filled('dari') ? Carbon::parse($request->query('dari')) : now()->startOfMonth();
        $sampai = $request->filled('sampai') ? Carbon::parse($request->query('sampai')) : now();

        if ($dari->gt($sampai)) {
            [$dari, $sampai] = [$sampai, $dari];
        }

        return [$dari, $sampai];
    }
}
