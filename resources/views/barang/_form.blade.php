<div class="form-grid">
    <x-form-input name="kode_barang" label="Kode barang" :value="$barang?->kode_barang" maxlength="10" placeholder="BRG-00001" />
    <x-form-input name="nama_barang" label="Nama barang" :value="$barang?->nama_barang" />

    <div class="field">
        <label for="kategori_id">Kategori</label>
        <select id="kategori_id" name="kategori_id" @class(['invalid' => $errors->has('kategori_id')])>
            <option value="">Pilih kategori</option>
            @foreach ($kategori as $k)
                <option value="{{ $k->id }}" @selected((string) old('kategori_id', $barang?->kategori_id) === (string) $k->id)>{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
        @error('kategori_id')<p class="field-error">{{ $message }}</p>@enderror
    </div>

    <div class="field">
        <label for="supplier_id">Supplier</label>
        <select id="supplier_id" name="supplier_id" @class(['invalid' => $errors->has('supplier_id')])>
            <option value="">Pilih supplier</option>
            @foreach ($suppliers as $s)
                <option value="{{ $s->id }}" @selected((string) old('supplier_id', $barang?->supplier_id) === (string) $s->id)>{{ $s->nama_pt }}</option>
            @endforeach
        </select>
        @error('supplier_id')<p class="field-error">{{ $message }}</p>@enderror
    </div>

    <x-form-input name="stok" label="Stok" type="number" min="0" :value="$barang?->stok ?? 0" />
    <x-form-input name="harga_satuan" label="Harga satuan (Rp)" type="number" min="0" step="1" :value="$barang ? (int) $barang->harga_satuan : null" />
</div>
<div class="form-actions">
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.barang.index') }}" class="btn btn-ghost">Batal</a>
</div>
