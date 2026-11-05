# GudangSmart - File tambahan untuk proyek Laravel

Folder ini adalah lapisan file yang ditimpa/ditambahkan ke proyek Laravel baru `gudang-smart`.

## Cara memasang

1. Buka `.env` di proyek, pastikan:
   ```
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

## Akun demo (password: `password`)

| Role | Email |
|---|---|
| Manager Gudang | manager@gudangsmart.test |
| Staff Gudang | staff@gudangsmart.test |

## Peta soal ke file

| Soal | File |
|---|---|
| 1.1 Penjelasan MVC | `docs/penjelasan-mvc.md` |
| 1.2 Setup & .env | langkah 1 di atas |
| 1.3 Routing | `routes/web.php` |
| 2 Layout & komponen | `resources/views/layouts/app.blade.php`, `resources/views/components/*`, `resources/views/dashboard.blade.php` |
| 3 Migration, model, seeder, factory | `database/migrations/*`, `app/Models/*`, `database/factories/*`, `database/seeders/DatabaseSeeder.php` |
| 4 Relasi, validasi, fitur barang | `app/Models/*`, `app/Http/Requests/*`, `app/Http/Controllers/BarangController.php` |
| 5 Auth & CheckRole | `app/Http/Controllers/AuthController.php`, `app/Http/Middleware/CheckRole.php`, `routes/web.php` |

## Hak akses

- Manager: semua fitur (tambah/edit/hapus supplier dan barang, restore, hapus permanen, kategori, mutasi stok).
- Staff: melihat supplier, kategori, dan katalog barang; mencatat mutasi stok masuk/keluar.
- Staff yang membuka URL milik manager akan dikembalikan ke halaman login dengan pesan peringatan.
