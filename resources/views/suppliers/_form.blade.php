<div class="form-grid">
    <x-form-input name="kode_supplier" label="Kode supplier" :value="$supplier?->kode_supplier" maxlength="10" placeholder="SUP-0001" />
    <x-form-input name="nama_pt" label="Nama PT" :value="$supplier?->nama_pt" />
    <x-form-input name="email" label="Email" type="email" :value="$supplier?->email" />
    <x-form-input name="telepon" label="Telepon" :value="$supplier?->telepon" />
    <div class="field full">
        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" rows="3" @class(['invalid' => $errors->has('alamat')])>{{ old('alamat', $supplier?->alamat) }}</textarea>
        @error('alamat')<p class="field-error">{{ $message }}</p>@enderror
    </div>
</div>
<div class="form-actions">
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-ghost">Batal</a>
</div>
