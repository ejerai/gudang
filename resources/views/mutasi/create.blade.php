@extends('layouts.app')

@section('title', 'Catat Mutasi Stok')

@section('content')
    <div class="page-head"><div><h1>Catat mutasi stok</h1><p>Stok barang diperbarui otomatis setelah disimpan.</p></div></div>
    <div class="card">
        <form method="POST" action="{{ route('admin.mutasi.store') }}">
            @csrf
            <div class="form-grid">
                <div class="field full">
                    <label for="barang_id">Barang</label>
                    <select id="barang_id" name="barang_id" @class(['invalid' => $errors->has('barang_id')])>
                        <option value="">Pilih barang</option>
                        @foreach ($barang as $b)
                            <option value="{{ $b->id }}" @selected((string) old('barang_id') === (string) $b->id)>{{ $b->kode_barang }} - {{ $b->nama_barang }} (stok {{ $b->stok }})</option>
                        @endforeach
                    </select>
                    @error('barang_id')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="jenis">Jenis mutasi</label>
                    <select id="jenis" name="jenis" @class(['invalid' => $errors->has('jenis')])>
                        <option value="masuk" @selected(old('jenis') === 'masuk')>Barang masuk</option>
                        <option value="keluar" @selected(old('jenis') === 'keluar')>Barang keluar</option>
                    </select>
                    @error('jenis')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <x-form-input name="jumlah" label="Jumlah" type="number" min="1" />

                <div class="field full">
                    <label for="keterangan">Keterangan (opsional)</label>
                    <textarea id="keterangan" name="keterangan" rows="2" @class(['invalid' => $errors->has('keterangan')])>{{ old('keterangan') }}</textarea>
                    @error('keterangan')<p class="field-error">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan mutasi</button>
                <a href="{{ route('admin.mutasi.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>
@endsection
