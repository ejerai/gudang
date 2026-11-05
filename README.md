# Gudang
<img width="1920" height="1080" alt="Screenshot (571)" src="https://github.com/user-attachments/assets/e3c9e803-7334-4161-9d45-88023773571c" />
Folder ini adalah lapisan file yang ditimpa/ditambahkan ke proyek Laravel baru `gudang-smart`.

## set-up
```
1. Buka `.env` di proyek, pastikan:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_gudang_smart
   DB_USERNAME=root
   DB_PASSWORD=
   ```
2. Salin seluruh isi folder ini ke folder `gudang-smart` (pilih "Replace" jika diminta; yang tertimpa hanya `routes/web.php`, `database/seeders/DatabaseSeeder.php`, dan `README.md`).
3. Di terminal Laragon, dalam folder `gudang-smart`:
   ```
   php artisan migrate:fresh --seed
   php artisan serve
   ```
4. Buka http://localhost:8000 dan login.

## account (password: `password`)

| Role | Email |
|---|---|
| Manager Gudang | manager@gudangsmart.test |
| Staff Gudang | staff@gudangsmart.test |

<br>

## PROJECT UTS - PEMOGRAMAN WEB TINGKAT MAHIR
Nomor 1 — Fondasi Proyek (MVC, Environment, Routing)

### 1. Alur Request–Response MVC pada Dashboard

Ketika pengguna membuka `/dashboard`:

1. **Request masuk** — browser mengirim `GET /dashboard` ke `public/index.php`, lalu Laravel melakukan bootstrap (`bootstrap/app.php`) dan menjalankan middleware web (session, CSRF).
2. **Routing** — `routes/web.php` mencocokkan URL ke `DashboardController@index` (named route `dashboard`). Rute ini dilindungi middleware `auth` dan `CheckRole:manager,staff`; pengguna tanpa sesi diarahkan ke halaman login dengan pesan peringatan.
3. **Controller** — `DashboardController` menerima request dan meminta data ke Model.
4. **Model** — Eloquent (`Supplier`, `Barang`, `MutasiStok`) menjalankan query ke database `db_gudang_smart`: `Supplier::count()`, `Barang::count()`, `Barang::sum('stok')`, dan 5 mutasi terakhir (`with(['barang','user'])` untuk mencegah N+1).
5. **View** — controller memanggil `view('dashboard', [...])`. File `dashboard.blade.php` memakai `@extends('layouts.app')` beserta komponen `<x-sidebar>`, `<x-navbar>`, dan `<x-alert>`, lalu dikompilasi menjadi HTML.
6. **Response** — HTML dikirim kembali ke browser dan ditampilkan bersama `public/css/gudangsmart.css`.

Ringkasnya: **Route → Controller → Model → Database → Controller → View → Response**.

### 2. Setup & Konfigurasi Environment

```bash
composer create-project laravel/laravel gudang-smart
cd gudang-smart
cp .env.example .env
php artisan key:generate
```

Buat database `db_gudang_smart` di MariaDB/MySQL, lalu atur `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_gudang_smart
DB_USERNAME=root
DB_PASSWORD=
```

Setelah itu jalankan `php artisan migrate --seed` dan `php artisan serve`.

### 3. Routing (`routes/web.php`)

| Kebutuhan | Implementasi |
|---|---|
| Rute konvensional | `Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')` |
| Route Group + prefix | `Route::prefix('admin')->name('admin.')->group(...)` |
| Resource Route Supplier | `Route::resource('suppliers', SupplierController::class)` → `/admin/suppliers` |
| Resource Route Barang | `Route::resource('barang', BarangController::class)` → `/admin/barang` |
| Named Routes | Semua rute bernama, mis. `dashboard`, `admin.suppliers.index`, `admin.barang.index`, `admin.mutasi.create` |

Rute dibungkus middleware `auth` dan `CheckRole` agar akses sesuai peran (`manager` / `staff`).

## Nomor 2 — Antarmuka dengan Blade Templating Engine

### 1. Master Layout Inheritance

File: `resources/views/layouts/app.blade.php`

Master layout menjadi kerangka baku semua halaman dan memiliki empat bagian utama:

| Bagian | Implementasi |
|---|---|
| **Header** | `<x-navbar />` (topbar) |
| **Navigation / Sidebar** | `<x-sidebar />` |
| **Content Area** | `<main class="content">` dengan `@yield('content')` |
| **Footer** | `<footer class="footer">` berisi hak cipta dan nama sistem |

Directive yang dipakai:

- `@yield('title')` — judul halaman di tag `<title>` dan topbar.
- `@yield('content')` — tempat isi tiap halaman disisipkan.
- `@stack('scripts')` — tempat halaman menambahkan JavaScript sendiri.
- `@extends('layouts.app')` dan `@section('title', ...)` / `@section('content') ... @endsection` — dipakai di setiap halaman turunan (dashboard, katalog, mutasi, dsb.).

Contoh pemakaian pada halaman turunan:

```blade
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- isi halaman --}}
@endsection
```

Layout juga menampilkan pesan flash (`success`, `error`, `warning`) secara otomatis lewat `<x-alert>`, sehingga halaman turunan tidak perlu menulis ulang kode notifikasi.

### 2. Reusable Blade Components

Seluruh komponen berada di `resources/views/components/`.

| Komponen | File | Fungsi |
|---|---|---|
| `<x-navbar>` | `navbar.blade.php` | Navigasi atas: tanggal, kolom pencarian barang, **status koneksi sistem** ("Sistem terhubung" / "Koneksi terputus" berdasarkan pengecekan koneksi database), ikon notifikasi stok menipis, dan **nama pengguna aktif** beserta perannya |
| `<x-sidebar>` | `sidebar.blade.php` | Navigasi samping: **Dashboard, Katalog Barang, Mutasi Stok, Supplier, Kategori Barang**; menu Laporan dan Barang Terhapus hanya tampil untuk Manager. Menu aktif ditandai dengan `request()->routeIs(...)` |
| `<x-alert>` | `alert.blade.php` | Pesan notifikasi fleksibel dengan props `type` |

Komponen `<x-alert>` memakai props sehingga satu komponen melayani semua jenis pesan:

```blade
@props(['type' => 'success'])
<div {{ $attributes->class(['alert', 'alert-' . $type]) }} role="alert">{{ $slot }}</div>
```

```blade
<x-alert type="success">Data berhasil disimpan.</x-alert>
<x-alert type="danger">Data gagal diproses.</x-alert>
<x-alert type="warning">Silakan login terlebih dahulu.</x-alert>
```

Nilai `type` menentukan kelas CSS (`alert-success`, `alert-danger`, `alert-warning`, `alert-info`), dan nilai bawaannya adalah `success`.

Selain tiga komponen wajib, ada komponen pendukung: `<x-icon>`, `<x-logo>`, dan `<x-form-input>`.

### 3. Halaman Dashboard

File: `resources/views/dashboard.blade.php` (memakai `@extends('layouts.app')`).

Halaman menampilkan **3 Card Ringkasan** dengan data dari `DashboardController`:

| Card | Sumber data |
|---|---|
| Total Supplier | `Supplier::count()` |
| Total Jenis Barang | `Barang::count()` |
| Total Stok Fisik Gudang | `Barang::sum('stok')` |

Di bawah kartu ditampilkan tabel "Mutasi stok terbaru" (5 transaksi terakhir) dengan tautan ke halaman Mutasi Stok. Tampilan dibuat responsif: pada layar kecil sidebar berubah menjadi laci yang dibuka lewat tombol menu.

---

## Nomor 3 — Basis Data Relasional dengan ORM

### 1. Skema Migration

File berada di `database/migrations/`.

**`suppliers`**

| Kolom | Tipe |
|---|---|
| id | bigint, primary key |
| kode_supplier | string(10), **unique** |
| nama_pt | string(150) |
| email | string(150) |
| telepon | string(30) |
| alamat | text |
| created_at, updated_at | timestamps |
| deleted_at | **soft deletes** |

**`kategori_barang`**

| Kolom | Tipe |
|---|---|
| id | bigint, primary key |
| kode_kategori | string(20), **unique** |
| nama_kategori | string(100) |
| created_at, updated_at | timestamps |

**`barang`**

| Kolom | Tipe |
|---|---|
| id | bigint, primary key |
| kode_barang | string(10), **unique** |
| kategori_id | **foreign key** → `kategori_barang.id` |
| supplier_id | **foreign key** → `suppliers.id` |
| nama_barang | string(150) |
| stok | integer, **default 0** |
| harga_satuan | decimal(15,2) |
| created_at, updated_at | timestamps |
| deleted_at | **soft deletes** |

Foreign key memakai `restrictOnDelete()`, sehingga kategori atau supplier yang masih memiliki barang tidak dapat dihapus permanen.

Contoh potongan migration tabel `barang`:

```php
Schema::create('barang', function (Blueprint $table) {
    $table->id();
    $table->string('kode_barang', 10)->unique();
    $table->foreignId('kategori_id')->constrained('kategori_barang')->restrictOnDelete();
    $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
    $table->string('nama_barang', 150);
    $table->integer('stok')->default(0);
    $table->decimal('harga_satuan', 15, 2);
    $table->timestamps();
    $table->softDeletes();
});
```

### 2. Model Eloquent

| Model | Tabel | Keterangan |
|---|---|---|
| `Supplier` | `suppliers` | Memakai trait **`SoftDeletes`** |
| `KategoriBarang` | `kategori_barang` | Tanpa soft delete |
| `Barang` | `barang` | Memakai trait **`SoftDeletes`** |

Setiap model mendefinisikan `$table` (karena nama tabel tidak mengikuti konvensi jamak bahasa Inggris) dan `$fillable` untuk keamanan mass assignment. Model `Supplier` dan `Barang` juga memakai `HasFactory` agar bisa dibuat lewat factory.

```php
class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';
    protected $fillable = ['kode_barang', 'kategori_id', 'supplier_id', 'nama_barang', 'stok', 'harga_satuan'];
}
```

Dengan `SoftDeletes`, perintah `delete()` hanya mengisi kolom `deleted_at` sehingga data masih dapat dipulihkan (`restore()`) dan baru benar-benar hilang saat `forceDelete()`.

### 3. Factory dan Seeder

**Factory** (`database/factories/`)

- `SupplierFactory` — membuat kode supplier unik (`SUP-xxxx`), nama PT, email, telepon, dan alamat dengan lokal Indonesia (`fake('id_ID')`).
- `BarangFactory` — membuat kode barang unik (`BRG-xxxxx`), nama barang realistis (gabungan produk industri dan spesifikasi, mis. "Baut Baja M8"), stok acak 0–500, harga satuan acak, serta mengambil `kategori_id` dan `supplier_id` secara acak dari data yang sudah ada.

**`DatabaseSeeder`** — dijalankan dengan `php artisan db:seed` dan mengisi otomatis:

| Data | Jumlah | Cara |
|---|---|---|
| Kategori barang | **5** | Data tetap: Bahan Baku, Komponen Mekanik, Komponen Elektrik, Alat Keselamatan Kerja, Peralatan dan Perkakas |
| Supplier | **10** | `Supplier::factory()->count(10)->create()` |
| Barang | **20** | `Barang::factory()->count(20)->create()` |

Seeder juga membuat dua akun uji untuk kebutuhan autentikasi (`manager@gudangsmart.test` dan `staff@gudangsmart.test`, password `password`) dan contoh riwayat mutasi 30 hari terakhir lewat `MutasiContohSeeder`.

Urutan pengisian sengaja kategori → supplier → barang, karena factory barang membutuhkan kategori dan supplier yang sudah ada.

### Cara Menjalankan

```bash
php artisan migrate:fresh --seed
```

Perintah ini membuat ulang seluruh tabel lalu mengisinya dengan data awal.
