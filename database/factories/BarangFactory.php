<?php

namespace Database\Factories;

use App\Models\KategoriBarang;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    public function definition(): array
    {
        $produk = [
            'Baut Baja', 'Mur Stainless', 'Bearing Roller', 'Kabel Tembaga', 'Pipa Galvanis',
            'Oli Pelumas', 'Plat Besi', 'Sarung Tangan Safety', 'Helm Proyek', 'Kawat Las',
            'Gerinda Tangan', 'Selang Hidrolik', 'Sealant Industri', 'Lampu Industri',
        ];
        $spesifikasi = ['M8', 'M10', 'Tipe A', 'Heavy Duty', 'Grade 304', '2 inch', '5 Liter', '1 mm', 'Standar SNI'];

        return [
            'kode_barang'  => 'BRG-' . fake()->unique()->numerify('#####'),
            'kategori_id'  => KategoriBarang::query()->inRandomOrder()->value('id'),
            'supplier_id'  => Supplier::query()->inRandomOrder()->value('id'),
            'nama_barang'  => fake()->randomElement($produk) . ' ' . fake()->randomElement($spesifikasi),
            'stok'         => fake()->numberBetween(0, 500),
            'harga_satuan' => fake()->numberBetween(5, 2500) * 1000,
        ];
    }
}
