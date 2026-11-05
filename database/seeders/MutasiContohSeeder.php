<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\MutasiStok;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// Data contoh mutasi 14 hari terakhir agar grafik dashboard terisi.
// Stok barang ikut disesuaikan supaya tetap konsisten dengan riwayat mutasi.
class MutasiContohSeeder extends Seeder
{
    public function run(): void
    {
        $barang = Barang::all();
        $petugas = User::pluck('id');

        if ($barang->isEmpty() || $petugas->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($barang, $petugas) {
            for ($i = 0; $i < 45; $i++) {
                $b = $barang->random()->fresh();
                $masuk = fake()->boolean(50) || $b->stok < 5;
                $jumlah = $masuk ? fake()->numberBetween(5, 60) : fake()->numberBetween(1, max(1, min(40, $b->stok)));

                if (! $masuk && $jumlah > $b->stok) {
                    continue;
                }

                $b->stok += $masuk ? $jumlah : -$jumlah;
                $b->save();

                $waktu = now()->subDays(fake()->numberBetween(0, 13))->setTime(fake()->numberBetween(8, 17), fake()->numberBetween(0, 59));

                $m = new MutasiStok([
                    'barang_id'  => $b->id,
                    'user_id'    => $petugas->random(),
                    'jenis'      => $masuk ? 'masuk' : 'keluar',
                    'jumlah'     => $jumlah,
                    'keterangan' => $masuk ? 'Penerimaan dari supplier' : 'Pemakaian produksi',
                ]);
                $m->created_at = $waktu;
                $m->updated_at = $waktu;
                $m->save();
            }
        });
    }
}
