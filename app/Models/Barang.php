<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'kategori_id',
        'supplier_id',
        'nama_barang',
        'stok',
        'harga_satuan',
    ];

    // Barang dimiliki satu supplier (withTrashed agar tetap tampil walau supplier dihapus sementara)
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withTrashed();
    }

    // Barang dimiliki satu kategori
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }

    public function mutasi(): HasMany
    {
        return $this->hasMany(MutasiStok::class, 'barang_id');
    }
}
