@extends('layouts.app')

@section('title', 'Barang Terhapus')

@section('content')
    <div class="page-head">
        <div>
            <h1>Barang terhapus</h1>
            <p>Pulihkan barang, atau hapus permanen jika sudah tidak dibutuhkan.</p>
        </div>
        <div class="actions"><a href="{{ route('admin.barang.index') }}" class="btn btn-ghost">Kembali ke katalog</a></div>
    </div>

    <div class="card flush">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Kode</th><th>Nama barang</th><th>Kategori</th><th>Supplier</th><th>Dihapus</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($barang as $b)
                        <tr>
                            <td>{{ $b->kode_barang }}</td>
                            <td>{{ $b->nama_barang }}</td>
                            <td>{{ $b->kategori?->nama_kategori }}</td>
                            <td>{{ $b->supplier?->nama_pt }}</td>
                            <td>{{ $b->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="actions-cell">
                                <form method="POST" action="{{ route('admin.barang.restore', $b->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-ghost btn-sm">Pulihkan</button>
                                </form>
                                <form method="POST" action="{{ route('admin.barang.force-delete', $b->id) }}" onsubmit="return confirm('Hapus permanen? Tindakan ini tidak bisa dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus permanen</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">Tidak ada barang yang terhapus.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $barang->links('vendor.pagination.gudang') }}
    </div>
@endsection
