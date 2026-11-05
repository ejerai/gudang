@php
    $user = auth()->user();
    $isManager = $user?->role === 'manager';
    $batas = \App\Http\Controllers\LaporanController::BATAS_STOK_MENIPIS;

    try {
        $totalBarang = \App\Models\Barang::count();
        $totalSupplier = \App\Models\Supplier::count();
        $menipis = \App\Models\Barang::where('stok', '<=', $batas)->count();
        $daftarMenipis = \App\Models\Barang::where('stok', '<=', $batas)->orderBy('stok')->orderBy('nama_barang')->limit(3)->get(['id', 'nama_barang', 'stok']);
    } catch (\Throwable $e) {
        $totalBarang = $totalSupplier = $menipis = 0;
        $daftarMenipis = collect();
    }
    $aman = max($totalBarang - $menipis, 0);
    $persenAman = $totalBarang > 0 ? round($aman / $totalBarang * 100) : 100;
    $inisial = collect(explode(' ', trim($user?->name ?? 'U')))->filter()->take(2)->map(fn ($k) => mb_strtoupper(mb_substr($k, 0, 1)))->implode('');
@endphp
<aside class="sidebar" id="sidebar" aria-label="Navigasi utama">
    <div class="side-top">
        <a class="brand" href="{{ route('dashboard') }}">
            <x-logo class="logo" />
            GudangSmart
        </a>
        <button type="button" class="icon-btn" data-nav-toggle aria-label="Tutup menu"><x-icon name="panel" /></button>
    </div>

    <nav class="side-nav">
        <div class="nav-label">Ruang kerja</div>
        <a class="nav-item @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}"><x-icon name="dashboard" />Dashboard</a>
        <a class="nav-item @if(request()->routeIs('admin.barang.*') && ! request()->routeIs('admin.barang.trash')) active @endif" href="{{ route('admin.barang.index') }}"><x-icon name="box" />Katalog Barang<span class="nav-count">{{ $totalBarang }}</span></a>
        <a class="nav-item @if(request()->routeIs('admin.mutasi.*')) active @endif" href="{{ route('admin.mutasi.index') }}"><x-icon name="swap" />Mutasi Stok</a>

        <div class="nav-label">Data master</div>
        <a class="nav-item @if(request()->routeIs('admin.suppliers.*')) active @endif" href="{{ route('admin.suppliers.index') }}"><x-icon name="truck" />Supplier<span class="nav-count">{{ $totalSupplier }}</span></a>
        <a class="nav-item @if(request()->routeIs('admin.kategori.*')) active @endif" href="{{ route('admin.kategori.index') }}"><x-icon name="tag" />Kategori Barang</a>

        @if ($isManager)
            <div class="nav-label">Manajer</div>
            <a class="nav-item @if(request()->routeIs('admin.laporan.*')) active @endif" href="{{ route('admin.laporan.index') }}"><x-icon name="chart" />Laporan</a>
            <a class="nav-item @if(request()->routeIs('admin.barang.trash')) active @endif" href="{{ route('admin.barang.trash') }}"><x-icon name="trash" />Barang Terhapus</a>
        @endif

        @if ($daftarMenipis->isNotEmpty())
            <div class="nav-label">Perlu diisi ulang</div>
            @foreach ($daftarMenipis as $b)
                <a class="nav-item" href="{{ route('admin.barang.index', ['q' => $b->nama_barang]) }}">
                    <i class="dot {{ $b->stok === 0 ? 'red' : '' }}"></i><span class="nm">{{ $b->nama_barang }}</span><span class="qty">{{ $b->stok }}</span>
                </a>
            @endforeach
        @endif
    </nav>

    <div class="side-bottom">
        <div class="health">
            <div class="ring" style="--p: {{ $persenAman }}"></div>
            <div><strong>{{ $aman }}</strong>/{{ $totalBarang }} barang stok aman</div>
            <div class="sub">Menipis jika stok ≤ {{ $batas }}</div>
            <a class="btn-dark" href="{{ $isManager ? route('admin.laporan.index') : route('admin.barang.index') }}">{{ $isManager ? 'Lihat laporan' : 'Lihat katalog' }}</a>
        </div>

        @if ($user)
            <div class="profile">
                <span class="avatar">{{ $inisial }}</span>
                <span class="who">
                    <strong>{{ $user->name }}</strong>
                    <small>{{ $isManager ? 'Manager Gudang' : 'Staff Gudang' }}</small>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="icon-btn" aria-label="Keluar" title="Keluar"><x-icon name="logout" /></button>
                </form>
            </div>
        @endif
    </div>
</aside>
