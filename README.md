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