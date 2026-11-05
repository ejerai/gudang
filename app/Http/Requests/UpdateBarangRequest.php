<?php

namespace App\Http\Requests;

use App\Models\Barang;
use Illuminate\Validation\Rule;

class UpdateBarangRequest extends StoreBarangRequest
{
    public function rules(): array
    {
        $barang = $this->route('barang');
        $id = $barang instanceof Barang ? $barang->id : $barang;

        $rules = parent::rules();

        // Kode boleh sama dengan miliknya sendiri, tetapi tidak boleh sama dengan barang lain
        $rules['kode_barang'] = [
            'required',
            'string',
            'max:10',
            Rule::unique('barang', 'kode_barang')->ignore($id),
        ];

        return $rules;
    }
}
