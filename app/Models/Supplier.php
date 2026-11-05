<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'suppliers';

    protected $fillable = ['kode_supplier', 'nama_pt', 'email', 'telepon', 'alamat'];

    // Satu supplier memasok banyak barang
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'supplier_id');
    }
}
