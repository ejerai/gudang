@extends('layouts.app')

@section('title', 'Catat Mutasi Stok')

@section('content')
    <div class="page-head"><div><h1>Catat mutasi stok</h1><p>Stok barang diperbarui otomatis setelah disimpan.</p></div></div>
    <div class="card">
        <form method="POST" action="{{ route('admin.mutasi.store') }}">
            @csrf
            <div class="form-grid">
                <div class="field full">
                    <label for="barang_cari">Barang</label>

                    {{-- Pemilih barang dengan pencarian (nama atau kode) --}}
                    <div class="picker" data-url="{{ route('admin.barang.cari') }}">
                        <input type="hidden" name="barang_id" id="barang_id" value="{{ $terpilih?->id }}">

                        <div class="picker-selected" @if (! $terpilih) hidden @endif>
                            <div class="t">
                                <strong data-f="nama">{{ $terpilih?->nama_barang }}</strong>
                                <small data-f="meta">{{ $terpilih ? $terpilih->kode_barang . ' · ' . ($terpilih->kategori?->nama_kategori ?? '-') : '' }}</small>
                            </div>
                            <span class="badge badge-info" data-f="stok">{{ $terpilih ? 'Stok ' . $terpilih->stok : '' }}</span>
                            <button type="button" class="btn btn-ghost btn-sm" data-ganti>Ganti</button>
                        </div>

                        <div class="picker-search" @if ($terpilih) hidden @endif>
                            <x-icon name="search" />
                            <input type="text" id="barang_cari" placeholder="Ketik nama atau kode barang" autocomplete="off"
                                   role="combobox" aria-expanded="false" aria-controls="barang_list" aria-autocomplete="list"
                                   @class(['invalid' => $errors->has('barang_id')])>
                        </div>
                        <ul class="picker-list" id="barang_list" role="listbox" hidden></ul>
                    </div>
                    <p class="hint">Cari berdasarkan nama atau kode, lalu pilih dari daftar.</p>
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

@push('scripts')
<script>
(function () {
    var root = document.querySelector('.picker');
    if (!root) return;

    var url = root.dataset.url;
    var hidden = root.querySelector('input[type="hidden"]');
    var kotakCari = root.querySelector('.picker-search');
    var input = document.getElementById('barang_cari');
    var list = document.getElementById('barang_list');
    var terpilih = root.querySelector('.picker-selected');
    var hasil = [], aktif = -1, timer = null, permintaan = 0;

    function tutup() {
        list.hidden = true;
        input.setAttribute('aria-expanded', 'false');
        aktif = -1;
    }

    function tandai(i) {
        var li = list.querySelectorAll('li[data-i]');
        li.forEach(function (el, n) { el.setAttribute('aria-selected', n === i ? 'true' : 'false'); });
        aktif = i;
        if (li[i]) li[i].scrollIntoView({ block: 'nearest' });
    }

    function gambar(daftar) {
        list.textContent = '';
        if (!daftar.length) {
            var kosong = document.createElement('li');
            kosong.className = 'none';
            kosong.textContent = 'Barang tidak ditemukan';
            list.appendChild(kosong);
        }
        daftar.forEach(function (b, i) {
            var li = document.createElement('li');
            li.setAttribute('role', 'option');
            li.setAttribute('aria-selected', 'false');
            li.dataset.i = i;

            var t = document.createElement('div'); t.className = 't';
            var nama = document.createElement('strong'); nama.textContent = b.nama;
            var meta = document.createElement('small'); meta.textContent = b.kode + ' · ' + (b.kategori || '-');
            t.appendChild(nama); t.appendChild(meta);

            var stok = document.createElement('span');
            stok.className = 'badge ' + (b.stok <= 10 ? 'badge-warning' : '');
            stok.textContent = 'Stok ' + b.stok;

            li.appendChild(t); li.appendChild(stok);
            li.addEventListener('mousedown', function (e) { e.preventDefault(); pilih(b); });
            list.appendChild(li);
        });
        list.hidden = false;
        input.setAttribute('aria-expanded', 'true');
        aktif = -1;
    }

    function cari() {
        var id = ++permintaan;
        fetch(url + '?q=' + encodeURIComponent(input.value.trim()), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
        .then(function (r) {
            if (!r.ok) throw new Error('gagal');
            return r.json();
        })
        .then(function (data) {
            if (id !== permintaan) return; // abaikan respons yang sudah usang
            hasil = data;
            gambar(hasil);
        })
        .catch(function () {
            list.textContent = '';
            var li = document.createElement('li');
            li.className = 'none';
            li.textContent = 'Pencarian gagal. Coba lagi.';
            list.appendChild(li);
            list.hidden = false;
        });
    }

    function pilih(b) {
        hidden.value = b.id;
        terpilih.querySelector('[data-f="nama"]').textContent = b.nama;
        terpilih.querySelector('[data-f="meta"]').textContent = b.kode + ' · ' + (b.kategori || '-');
        terpilih.querySelector('[data-f="stok"]').textContent = 'Stok ' + b.stok;
        terpilih.hidden = false;
        kotakCari.hidden = true;
        tutup();
        var jumlah = document.getElementById('jumlah');
        if (jumlah) jumlah.focus();
    }

    root.querySelector('[data-ganti]').addEventListener('click', function () {
        hidden.value = '';
        terpilih.hidden = true;
        kotakCari.hidden = false;
        input.value = '';
        input.focus();
        cari();
    });

    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(cari, 220);
    });
    input.addEventListener('focus', cari);
    input.addEventListener('blur', function () { setTimeout(tutup, 120); });

    input.addEventListener('keydown', function (e) {
        var n = list.querySelectorAll('li[data-i]').length;
        if (e.key === 'ArrowDown') { e.preventDefault(); if (n) tandai((aktif + 1) % n); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); if (n) tandai((aktif - 1 + n) % n); }
        else if (e.key === 'Enter') {
            if (!list.hidden && n) { e.preventDefault(); pilih(hasil[aktif >= 0 ? aktif : 0]); }
        }
        else if (e.key === 'Escape') { tutup(); }
    });
})();
</script>
@endpush
