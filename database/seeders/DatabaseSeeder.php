<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun login (password: password)
        $akun = [
            ['Manager Gudang', 'manager@gudangsmart.test', 'manager'],
            ['Staff Gudang', 'staff@gudangsmart.test', 'staff'],
        ];

        foreach ($akun as [$nama, $email, $role]) {
            $user = User::firstOrNew(['email' => $email]);
            $user->name = $nama;
            $user->password = Hash::make('password');
            $user->role = $role;
            $user->save();
        }

        // 5 kategori barang
        $kategori = [
            ['KAT-001', 'Bahan Baku'],
            ['KAT-002', 'Komponen Mekanik'],
            ['KAT-003', 'Komponen Elektrik'],
            ['KAT-004', 'Alat Keselamatan Kerja'],
            ['KAT-005', 'Peralatan dan Perkakas'],
        ];

        foreach ($kategori as [$kode, $nama]) {
            KategoriBarang::updateOrCreate(['kode_kategori' => $kode], ['nama_kategori' => $nama]);
        }

        // 10 supplier dan 20 barang (butuh kategori & supplier sudah ada)
        Supplier::factory()->count(10)->create();
        Barang::factory()->count(20)->create();
    }
}
