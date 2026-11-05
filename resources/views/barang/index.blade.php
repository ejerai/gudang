@extends('layouts.app')

@section('title', 'Katalog Barang')

@section('content')
    @php $isManager = auth()->user()->role === 'manager'; @endphp

    <div class="page-head">
        <div>
            <h1>Katalog barang</h1>
            <p>Cari barang berdasarkan nama, kode, atau kategori.</p>
        </div>
        @if ($isManager)
            <div class="actions">
                <a href="{{ route('admin.barang.trash') }}" class="btn btn-ghost">Barang terhapus</a>
                <a href="{{ route('admin.barang.create') }}" class="btn btn-primary">Tambah barang</a>
            </div>
        @endif
    </div>

    <div class="card flush">
        <form method="GET" action="{{ route('admin.barang.index') }}" class="toolbar">
            <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama atau kode barang">
            <select name="kategori_id" aria-label="Filter kategori">
                <option value="">Semua kategori</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}" @selected((string) $kategoriId === (string) $k->id)>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-ghost">Terapkan</button>
            @if ($search !== '' || $kategoriId)
                <a href="{{ route('admin.barang.index') }}" class="btn btn-ghost">Reset</a>
            @endif
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th><th>Nama barang</th><th>Kategori</th><th>Supplier</th>
                        <th class="num">Stok</th><th class="num">Harga satuan</th>
                        @if ($isManager)<th></th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barang as $b)
                        <tr>
                            <td>{{ $b->kode_barang }}</td>
                            <td>{{ $b->nama_barang }}</td>
                            <td>{{ $b->kategori?->nama_kategori }}</td>
                            <td>{{ $b->supplier?->nama_pt }}</td>
                            <td class="num">
                                <span class="badge {{ $b->stok <= 10 ? 'badge-warning' : '' }}">{{ number_format($b->stok, 0, ',', '.') }}</span>
                            </td>
                            <td class="num">Rp {{ number_format($b->harga_satuan, 0, ',', '.') }}</td>
                            @if ($isManager)
                                <td class="actions-cell">
                                    <a href="{{ route('admin.barang.edit', $b) }}" class="btn btn-ghost btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.barang.destroy', $b) }}" onsubmit="return confirm('Pindahkan barang ini ke data terhapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty">Tidak ada barang yang cocok dengan pencarian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $barang->links('vendor.pagination.gudang') }}
    </div>
@endsection
