<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_barang'  => ['required', 'string', 'max:10', 'unique:barang,kode_barang'],
            'kategori_id'  => ['required', 'exists:kategori_barang,id'],
            'supplier_id'  => ['required', 'exists:suppliers,id'],
            'nama_barang'  => ['required', 'string', 'min:3', 'max:150'],
            'stok'         => ['required', 'numeric', 'integer', 'min:0'],
            'harga_satuan' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'            => ':attribute wajib diisi.',
            'max'                 => ':attribute maksimal :max karakter.',
            'min.string'          => ':attribute minimal :min karakter.',
            'stok.min'            => 'Stok tidak boleh negatif.',
            'harga_satuan.min'    => 'Harga satuan tidak boleh negatif.',
            'numeric'             => ':attribute harus berupa angka.',
            'integer'             => ':attribute harus berupa bilangan bulat.',
            'unique'              => ':attribute sudah digunakan.',
            'exists'              => ':attribute yang dipilih tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_barang'  => 'Kode barang',
            'kategori_id'  => 'Kategori',
            'supplier_id'  => 'Supplier',
            'nama_barang'  => 'Nama barang',
            'stok'         => 'Stok',
            'harga_satuan' => 'Harga satuan',
        ];
    }
}
