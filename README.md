# GudangSmart 

Folder ini adalah lapisan file yang ditimpa/ditambahkan ke proyek Laravel baru `gudang-smart`.

## set-up

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

## account (password: `password`)

| Role | Email |
|---|---|
| Manager Gudang | manager@gudangsmart.test |
| Staff Gudang | staff@gudangsmart.test |