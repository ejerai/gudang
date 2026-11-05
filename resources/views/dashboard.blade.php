@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $isManager = auth()->user()->role === 'manager';
        $totalGerak = collect($grafik)->sum(fn ($g) => $g['masuk'] + $g['keluar']);
        // Tampilkan label sumbu-x secukupnya agar tidak berdesakan
        $langkah = $rentang <= 7 ? 1 : ($rentang <= 14 ? 1 : 3);
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

    {{-- Pergerakan stok --}}
    <section class="panel">
        <div class="panel-head">
            <h2>Pergerakan Stok</h2>
            <div class="seg" role="group" aria-label="Rentang waktu">
                @foreach ([7 => '7 Hari', 14 => '2 Minggu', 30 => '30 Hari'] as $nilai => $teks)
                    <a href="{{ route('dashboard', ['rentang' => $nilai]) }}" @class(['on' => $rentang === $nilai])>{{ $teks }}</a>
                @endforeach
            </div>
        </div>
        <div class="panel-body">
            <div class="chart" style="--n: {{ count($grafik) }}" role="img" aria-label="Grafik barang masuk dan keluar {{ $rentang }} hari terakhir">
                <div class="chart-grid"></div>
                @if ($totalGerak === 0)
                    <div class="chart-empty">Belum ada mutasi pada rentang ini.</div>
                @endif
                <div class="chart-cols">
                    @foreach ($grafik as $i => $g)
                        <div class="col">
                            <div class="bars">
                                <span class="bar in" style="height: {{ $g['masuk'] > 0 ? max(round($g['masuk'] / $puncak * 100), 2) : 0 }}%" title="{{ $g['label'] }}: masuk {{ number_format($g['masuk'], 0, ',', '.') }}"></span>
                                <span class="bar out" style="height: {{ $g['keluar'] > 0 ? max(round($g['keluar'] / $puncak * 100), 2) : 0 }}%" title="{{ $g['label'] }}: keluar {{ number_format($g['keluar'], 0, ',', '.') }}"></span>
                            </div>
                            <span @class(['lbl', 'skip' => $i % $langkah !== 0])>{{ $g['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="legend">
                <span><i style="background: var(--primary)"></i>Barang Masuk</span>
                <span><i style="background: var(--green)"></i>Barang Keluar</span>
            </div>
        </div>
    </section>

    {{-- Mutasi terbaru --}}
    <section class="panel">
        <div class="panel-head">
            <h2>Mutasi stok terbaru</h2>
            <div class="actions">
                <a href="{{ route('admin.mutasi.index') }}" class="btn btn-ghost btn-sm">Semua mutasi</a>
                @if ($isManager)
                    <a href="{{ route('admin.laporan.mutasi-csv', ['dari' => now()->startOfMonth()->format('Y-m-d'), 'sampai' => now()->format('Y-m-d')]) }}" class="btn btn-ghost btn-sm"><x-icon name="download" style="width:15px;height:15px" />Export CSV</a>
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
