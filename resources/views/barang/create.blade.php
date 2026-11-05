@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <div class="page-head"><div><h1>Tambah barang</h1></div></div>
    <div class="card">
        <form method="POST" action="{{ route('admin.barang.store') }}">
            @csrf
            @include('barang._form', ['barang' => null])
        </form>
    </div>
@endsection
