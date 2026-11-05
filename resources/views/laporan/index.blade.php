@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="page-head">
        <div>
            <h1>Laporan gudang</h1>
            <p>Periode mutasi: {{ $dari->format('d/m/Y') }} sampai {{ $sampai->format('d/m/Y') }}</p>
        </div>
        <div class="actions no-print">
            <a href="{{ route('admin.laporan.mutasi-csv', ['dari' => $dari->format('Y-m-d'), 'sampai' => $sampai->format('Y-m-d')]) }}" class="btn btn-ghost">Unduh CSV</a>
            <button type="button" class="btn btn-primary" onclick="window.print()">Cetak laporan</button>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.laporan.index') }}" class="card no-print">
        <div class="form-grid">
            <div class="field">
                <label for="dari">Dari tanggal</label>
                <input type="date" id="dari" name="dari" value="{{ $dari->format('Y-m-d') }}">
                @error('dari')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="sampai">Sampai tanggal</label>
                <input type="date" id="sampai" name="sampai" value="{{ $sampai->format('Y-m-d') }}">
                @error('sampai')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Terapkan periode</button>
    </form>

    <div class="stats">
        <div class="stat primary">
            <span>Nilai persediaan saat ini</span>
            <strong>Rp {{ number_format($totalNilai, 0, ',', '.') }}</strong>
        </div>
        <div class="stat">
            <span>Total stok fisik</span>
            <strong>{{ number_format($totalStok, 0, ',', '.') }}</strong>
        </div>
        <div class="stat">
            <span>Barang masuk (periode)</span>
            <strong>{{ number_format($totalMasuk, 0, ',', '.') }}</strong>
        </div>
        <div class="stat">
            <span>Barang keluar (periode)</span>
            <strong>{{ number_format($totalKeluar, 0, ',', '.') }}</strong>
        </div>
    </div>

    <div class="card flush">
        <div class="card-pad"><h2>Persediaan per kategori</h2></div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Kategori</th><th class="num">Jenis barang</th><th class="num">Total stok</th><th class="num">Nilai persediaan</th></tr>
                </thead>
                <tbody>
                    @forelse ($perKategori as $k)
                        <tr>
                            <td>{{ $k->nama_kategori }}</td>
                            <td class="num">{{ number_format($k->jenis, 0, ',', '.') }}</td>
                            <td class="num">{{ number_format($k->total_stok, 0, ',', '.') }}</td>
                            <td class="num">Rp {{ number_format($k->nilai, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card flush">
        <div class="card-pad">
            <h2>Stok menipis</h2>
            <p class="hint" style="margin:-8px 0 12px">Barang dengan stok {{ $batas }} atau kurang. Segera hubungi supplier untuk pengisian ulang.</p>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Kode</th><th>Nama barang</th><th>Kategori</th><th>Supplier</th><th class="num">Stok</th></tr>
                </thead>
                <tbody>
                    @forelse ($stokMenipis as $b)
                        <tr>
                            <td>{{ $b->kode_barang }}</td>
                            <td>{{ $b->nama_barang }}</td>
                            <td>{{ $b->kategori?->nama_kategori }}</td>
                            <td>{{ $b->supplier?->nama_pt }}<span class="sub">{{ $b->supplier?->telepon }}</span></td>
                            <td class="num"><span class="badge {{ $b->stok === 0 ? 'badge-danger' : 'badge-warning' }}">{{ $b->stok }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">Semua barang masih aman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card flush">
        <div class="card-pad"><h2>Rincian mutasi stok</h2></div>
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
                        <tr><td colspan="6" class="empty">Tidak ada mutasi pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="no-print">{{ $mutasi->links('vendor.pagination.gudang') }}</div>
    </div>
@endsection
