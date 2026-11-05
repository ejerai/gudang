<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiStok extends Model
{
    protected $table = 'mutasi_stok';

    protected $fillable = ['barang_id', 'user_id', 'jenis', 'jumlah', 'keterangan'];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id')->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
