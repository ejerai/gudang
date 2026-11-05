@extends('layouts.app')

@section('title', 'Kategori Barang')

@section('content')
    <div class="page-head">
        <div>
            <h1>Kategori barang</h1>
            <p>Pengelompokan barang di gudang.</p>
        </div>
    </div>

    @if (auth()->user()->role === 'manager')
        <div class="card">
            <h2>Tambah kategori</h2>
            <form method="POST" action="{{ route('admin.kategori.store') }}">
                @csrf
                <div class="form-grid">
                    <x-form-input name="kode_kategori" label="Kode kategori" placeholder="KAT-006" />
                    <x-form-input name="nama_kategori" label="Nama kategori" />
                </div>
                <button type="submit" class="btn btn-primary">Simpan kategori</button>
            </form>
        </div>
    @endif

    <div class="card flush">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Kode</th><th>Nama kategori</th><th class="num">Jumlah barang</th></tr></thead>
                <tbody>
                    @forelse ($kategori as $k)
                        <tr>
                            <td>{{ $k->kode_kategori }}</td>
                            <td>{{ $k->nama_kategori }}</td>
                            <td class="num">{{ $k->barang_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="empty">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
