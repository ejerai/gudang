@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
    <div class="page-head"><div><h1>Edit barang</h1><p>{{ $barang->nama_barang }}</p></div></div>
    <div class="card">
        <form method="POST" action="{{ route('admin.barang.update', $barang) }}">
            @csrf @method('PUT')
            @include('barang._form')
        </form>
    </div>
@endsection
