@php
    $isManager = auth()->user()?->role === 'manager';
@endphp
<aside class="sidebar">
    <nav>
        <a href="{{ route('dashboard') }}" @class(['active' => request()->routeIs('dashboard')])>Dashboard</a>
        <a href="{{ route('admin.suppliers.index') }}" @class(['active' => request()->routeIs('admin.suppliers.*')])>Supplier</a>
        <a href="{{ route('admin.kategori.index') }}" @class(['active' => request()->routeIs('admin.kategori.*')])>Kategori Barang</a>
        <a href="{{ route('admin.barang.index') }}" @class(['active' => request()->routeIs('admin.barang.*') && ! request()->routeIs('admin.barang.trash')])>Katalog Barang</a>
        <a href="{{ route('admin.mutasi.index') }}" @class(['active' => request()->routeIs('admin.mutasi.*')])>Mutasi Stok</a>
        @if ($isManager)
            <div class="sep"></div>
            <a href="{{ route('admin.barang.trash') }}" @class(['active' => request()->routeIs('admin.barang.trash')])>Barang Terhapus</a>
        @endif
    </nav>
</aside>
