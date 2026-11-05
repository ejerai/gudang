@php
    // Topbar: tanggal, pencarian barang, status koneksi sistem, ikon stok menipis, dan pengguna aktif
    $batasMenipis = \App\Http\Controllers\LaporanController::BATAS_STOK_MENIPIS;
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $online = true;
        $jumlahMenipis = \App\Models\Barang::where('stok', '<=', $batasMenipis)->count();
    } catch (\Throwable $e) {
        $online = false;
        $jumlahMenipis = 0;
    }
    $user = auth()->user();
    $isManager = $user?->role === 'manager';
    $tujuanBel = $isManager ? route('admin.laporan.index') . '#stok-menipis' : route('admin.barang.index');
@endphp
<header class="topbar">
    <button type="button" class="icon-btn menu-btn" data-nav-toggle aria-label="Buka atau tutup menu"><x-icon name="menu" /></button>
    <div class="crumb">
        <i class="live"></i>
        <strong>{{ now()->locale('id')->translatedFormat('l, j M') }}</strong>
        <span>@yield('title')</span>
    </div>
    <div class="topbar-right">
        <form method="GET" action="{{ route('admin.barang.index') }}" class="search" role="search">
            <x-icon name="search" />
            <input type="search" name="q" placeholder="Cari barang" aria-label="Cari barang">
        </form>
        <span class="status {{ $online ? 'status-on' : 'status-off' }}">
            <i></i>{{ $online ? 'Sistem terhubung' : 'Koneksi terputus' }}
        </span>
        <a href="{{ $tujuanBel }}" class="bell" aria-label="Barang stok menipis: {{ $jumlahMenipis }}" title="{{ $jumlahMenipis }} barang stok menipis">
            <x-icon name="package" />
            @if ($jumlahMenipis > 0)<b>{{ $jumlahMenipis > 99 ? '99+' : $jumlahMenipis }}</b>@endif
        </a>
        @if ($user)
            <span class="user">
                <strong>{{ $user->name }}</strong>
                <small>{{ $isManager ? 'Manager Gudang' : 'Staff Gudang' }}</small>
            </span>
        @endif
    </div>
</header>
