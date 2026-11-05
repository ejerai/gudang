# Soal 1.1 - Alur Request-Response MVC pada Dashboard GudangSmart

Ketika pengguna membuka halaman utama Dashboard Inventory (`/dashboard`), Laravel memproses permintaan dengan pola Model-View-Controller sebagai berikut.

1. **Request masuk.** Browser mengirim `GET /dashboard`. Semua request diterima oleh `public/index.php` (front controller), yang memuat aplikasi Laravel dan meneruskan request ke HTTP Kernel.

2. **Middleware.** Request melewati middleware global (session, CSRF, cookie). Lalu route `/dashboard` menjalankan middleware `auth` (apakah pengguna sudah login) dan `CheckRole:manager,staff` (apakah role pengguna diizinkan). Jika gagal, pengguna dialihkan ke halaman login dan alur berhenti di sini.

3. **Routing.** Router mencocokkan URL dan metode HTTP dengan daftar di `routes/web.php`. Route bernama `dashboard` diarahkan ke method `index()` pada `DashboardController`.

4. **Controller.** `DashboardController@index` adalah pengatur alur. Ia tidak menyimpan data dan tidak menulis HTML. Ia meminta data yang dibutuhkan kepada Model: jumlah supplier, jumlah jenis barang, total stok, dan lima mutasi terbaru.

5. **Model.** Model Eloquent (`Supplier`, `Barang`, `MutasiStok`) mewakili tabel di database `db_gudang_smart`. Pemanggilan seperti `Supplier::count()` dan `Barang::sum('stok')` diterjemahkan Eloquent menjadi query SQL ke MySQL/MariaDB. Hasilnya dikembalikan ke controller sebagai angka atau Collection.

6. **View.** Controller memanggil `view('dashboard', $data)`. Template Blade `dashboard.blade.php` mewarisi master layout `layouts/app.blade.php` (`@extends`), mengisi `@section('content')`, dan memakai komponen `<x-navbar>`, `<x-sidebar>`, `<x-alert>`. Blade mengisi data ke dalam HTML (`{{ $totalSupplier }}`) dan menghasilkan halaman HTML akhir.

7. **Response.** HTML dikirim kembali melalui middleware ke browser sebagai HTTP Response, lalu browser merender Dashboard dengan tiga kartu ringkasan.

Ringkasnya: **Route** menentukan tujuan, **Controller** mengatur alur, **Model** mengurus data, dan **View** menampilkan hasil. Pemisahan ini membuat kode mudah dirawat: tampilan bisa diubah tanpa menyentuh logika data, dan query bisa diubah tanpa menyentuh HTML.
