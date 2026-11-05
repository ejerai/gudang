@extends('layouts.app')

@section('title', 'Mutasi Stok')

@section('content')
    <div class="page-head">
        <div>
            <h1>Mutasi stok</h1>
            <p>Riwayat barang masuk dan keluar.</p>
        </div>
        <div class="actions"><a href="{{ route('admin.mutasi.create') }}" class="btn btn-primary">Catat mutasi</a></div>
    </div>

    <div class="card flush">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Waktu</th><th>Barang</th><th>Jenis</th><th class="num">Jumlah</th><th>Oleh</th><th>Keterangan</th></tr>
                </thead>
                <tbody>
                    @forelse ($mutasi as $m)
                        <tr>
                            <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $m->barang?->nama_barang }}<span class="sub">{{ $m->barang?->kode_barang }}</span></td>
                            <td><span class="badge {{ $m->jenis === 'masuk' ? 'badge-success' : 'badge-danger' }}">{{ $m->jenis === 'masuk' ? 'Masuk' : 'Keluar' }}</span></td>
                            <td class="num">{{ number_format($m->jumlah, 0, ',', '.') }}</td>
                            <td>{{ $m->user?->name }}</td>
                            <td>{{ $m->keterangan ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">Belum ada mutasi stok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $mutasi->links('vendor.pagination.gudang') }}
    </div>
@endsection
