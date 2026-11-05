<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $suppliers = Supplier::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($w) use ($search) {
                    $w->where('nama_pt', 'like', "%{$search}%")
                      ->orWhere('kode_supplier', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama_pt')
            ->paginate(10)
            ->withQueryString();

        return view('suppliers.index', compact('suppliers', 'search'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        Supplier::create($data);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate($this->rules($supplier), $this->messages());

        $supplier->update($data);

        return redirect()->route('admin.suppliers.index')->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete(); // soft delete

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }

    private function rules(?Supplier $supplier = null): array
    {
        return [
            'kode_supplier' => [
                'required', 'string', 'max:10',
                Rule::unique('suppliers', 'kode_supplier')->ignore($supplier?->id),
            ],
            'nama_pt' => ['required', 'string', 'max:150'],
            'email'   => ['required', 'email', 'max:150'],
            'telepon' => ['required', 'string', 'max:30'],
            'alamat'  => ['required', 'string'],
        ];
    }

    private function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max'      => ':attribute maksimal :max karakter.',
            'email'    => 'Format email tidak valid.',
            'unique'   => ':attribute sudah digunakan.',
        ];
    }
}
