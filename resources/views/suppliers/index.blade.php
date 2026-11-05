@extends('layouts.app')

@section('title', 'Supplier')

@section('content')
    @php $isManager = auth()->user()->role === 'manager'; @endphp

    <div class="page-head">
        <div>
            <h1>Supplier</h1>
            <p>Daftar pemasok barang gudang.</p>
        </div>
        @if ($isManager)
            <div class="actions">
                <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">Tambah supplier</a>
            </div>
        @endif
    </div>

    <div class="card flush">
        <form method="GET" action="{{ route('admin.suppliers.index') }}" class="toolbar">
            <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama PT atau kode supplier">
            <button type="submit" class="btn btn-ghost">Cari</button>
            @if ($search !== '')
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-ghost">Reset</a>
            @endif
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th><th>Nama PT</th><th>Email</th><th>Telepon</th>
                        @if ($isManager)<th></th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $s)
                        <tr>
                            <td>{{ $s->kode_supplier }}</td>
                            <td>{{ $s->nama_pt }}<span class="sub">{{ $s->alamat }}</span></td>
                            <td>{{ $s->email }}</td>
                            <td>{{ $s->telepon }}</td>
                            @if ($isManager)
                                <td class="actions-cell">
                                    <a href="{{ route('admin.suppliers.edit', $s) }}" class="btn btn-ghost btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.suppliers.destroy', $s) }}" onsubmit="return confirm('Hapus supplier ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">Belum ada data supplier.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $suppliers->links('vendor.pagination.gudang') }}
    </div>
@endsection
