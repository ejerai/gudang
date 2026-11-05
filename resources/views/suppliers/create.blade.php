@extends('layouts.app')

@section('title', 'Tambah Supplier')

@section('content')
    <div class="page-head"><div><h1>Tambah supplier</h1></div></div>
    <div class="card">
        <form method="POST" action="{{ route('admin.suppliers.store') }}">
            @csrf
            @include('suppliers._form', ['supplier' => null])
        </form>
    </div>
@endsection
