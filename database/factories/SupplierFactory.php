<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    public function definition(): array
    {
        $id = fake('id_ID');

        return [
            'kode_supplier' => 'SUP-' . fake()->unique()->numerify('####'),
            'nama_pt'       => $id->company(),
            'email'         => fake()->unique()->safeEmail(),
            'telepon'       => $id->phoneNumber(),
            'alamat'        => $id->address(),
        ];
    }
}
