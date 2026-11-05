@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $isManager = auth()->user()->role === 'manager';
    @endphp

    <div class="page-head">
        <div>
            <h1>Dashboard</h1>
            <p>Ringkasan kondisi gudang saat ini.</p>
        </div>
        <div class="actions">
            <a href="{{ route('admin.mutasi.create') }}" class="btn btn-primary">Catat mutasi</a>
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

    {{-- Mutasi terbaru --}}
    <section class="panel">
        <div class="panel-head">
            <h2>Mutasi stok terbaru</h2>
            <div class="actions">
                <a href="{{ route('admin.mutasi.index') }}" class="btn btn-ghost btn-sm">Semua mutasi</a>
                @if ($isManager)
                    <a href="{{ route('admin.laporan.mutasi-csv', ['dari' => now()->startOfMonth()->format('Y-m-d'), 'sampai' => now()->format('Y-m-d')]) }}" class="btn btn-ghost btn-sm"><x-icon name="download" style="width:17px;height:17px" />Export CSV</a>
                @endif
            </div>
        </div>
        <div class="panel-body flush">
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
    </section>
@endsection
