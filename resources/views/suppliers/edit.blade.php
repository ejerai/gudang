@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
    <div class="page-head"><div><h1>Edit supplier</h1><p>{{ $supplier->nama_pt }}</p></div></div>
    <div class="card">
        <form method="POST" action="{{ route('admin.suppliers.update', $supplier) }}">
            @csrf @method('PUT')
            @include('suppliers._form')
        </form>
    </div>
@endsection
