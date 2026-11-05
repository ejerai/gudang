@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-head">
        <div>
            <h1>Dashboard</h1>
            <p>Ringkasan kondisi gudang saat ini.</p>
        </div>
    </div>

    <div class="stats">
        <div class="stat">
            <span>Total Supplier</span>
            <strong>{{ number_format($totalSupplier, 0, ',', '.') }}</strong>
        </div>
        <div class="stat">
            <span>Total Jenis Barang</span>
            <strong>{{ number_format($totalJenisBarang, 0, ',', '.') }}</strong>
        </div>
        <div class="stat primary">
            <span>Total Stok Fisik Gudang</span>
            <strong>{{ number_format($totalStok, 0, ',', '.') }}</strong>
        </div>
    </div>

    <div class="card flush">
        <div class="card-pad"><h2>Mutasi stok terbaru</h2></div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Waktu</th><th>Barang</th><th>Jenis</th><th class="num">Jumlah</th><th>Oleh</th></tr>
                </thead>
                <tbody>
                    @forelse ($mutasiTerakhir as $m)
                        <tr>
                            <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $m->barang?->nama_barang }}<span class="sub">{{ $m->barang?->kode_barang }}</span></td>
                            <td><span class="badge {{ $m->jenis === 'masuk' ? 'badge-success' : 'badge-danger' }}">{{ $m->jenis === 'masuk' ? 'Masuk' : 'Keluar' }}</span></td>
                            <td class="num">{{ number_format($m->jumlah, 0, ',', '.') }}</td>
                            <td>{{ $m->user?->name }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">Belum ada mutasi stok. Catat barang masuk atau keluar di menu Mutasi Stok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
