@php
    $user = auth()->user();
    $isManager = $user?->role === 'manager';
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
        <a class="nav-item @if(request()->routeIs('admin.barang.*') && ! request()->routeIs('admin.barang.trash')) active @endif" href="{{ route('admin.barang.index') }}"><x-icon name="box" />Katalog Barang</a>
        <a class="nav-item @if(request()->routeIs('admin.mutasi.*')) active @endif" href="{{ route('admin.mutasi.index') }}"><x-icon name="swap" />Mutasi Stok</a>

        <div class="nav-label">Data master</div>
        <a class="nav-item @if(request()->routeIs('admin.suppliers.*')) active @endif" href="{{ route('admin.suppliers.index') }}"><x-icon name="truck" />Supplier</a>
        <a class="nav-item @if(request()->routeIs('admin.kategori.*')) active @endif" href="{{ route('admin.kategori.index') }}"><x-icon name="tag" />Kategori Barang</a>

        @if ($isManager)
            <div class="nav-label">Manajer</div>
            <a class="nav-item @if(request()->routeIs('admin.laporan.*')) active @endif" href="{{ route('admin.laporan.index') }}"><x-icon name="chart" />Laporan</a>
            <a class="nav-item @if(request()->routeIs('admin.barang.trash')) active @endif" href="{{ route('admin.barang.trash') }}"><x-icon name="trash" />Barang Terhapus</a>
        @endif

    </nav>

    <div class="side-bottom">
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
