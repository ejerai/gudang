<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MutasiStokController;
use App\Http\Controllers\SupplierController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

// Halaman depan (landing page)
Route::get('/', LandingController::class)->name('landing');

// Hanya untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Daftar peran yang diizinkan (dipakai bersama middleware auth)
$semuaPeran = CheckRole::class . ':manager,staff';
$hanyaManager = CheckRole::class . ':manager';

Route::middleware('auth')->group(function () use ($semuaPeran, $hanyaManager) {

    // Rute konvensional dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware($semuaPeran)
        ->name('dashboard');

    // Route group dengan prefix "admin" dan nama berawalan "admin."
    Route::prefix('admin')->name('admin.')->group(function () use ($semuaPeran, $hanyaManager) {

        // Manager + Staff: melihat data & mencatat mutasi stok
        Route::middleware($semuaPeran)->group(function () {
            // Pencarian barang (JSON) untuk form mutasi stok
            Route::get('barang/cari', [BarangController::class, 'cari'])->name('barang.cari');

            Route::resource('suppliers', SupplierController::class)->only(['index']);
            Route::resource('barang', BarangController::class)->only(['index']);
            Route::resource('kategori', KategoriBarangController::class)->only(['index']);
            Route::resource('mutasi', MutasiStokController::class)->only(['index', 'create', 'store']);
        });

        // Manager saja: tambah/edit/hapus, restore, hapus permanen
        Route::middleware($hanyaManager)->group(function () {
            Route::get('barang/trash', [BarangController::class, 'trash'])->name('barang.trash');
            Route::patch('barang/{id}/restore', [BarangController::class, 'restore'])->name('barang.restore');
            Route::delete('barang/{id}/force', [BarangController::class, 'forceDelete'])->name('barang.force-delete');

            Route::resource('suppliers', SupplierController::class)->except(['index', 'show']);
            Route::resource('barang', BarangController::class)->except(['index', 'show']);
            Route::resource('kategori', KategoriBarangController::class)->only(['store']);

            Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
            Route::get('laporan/mutasi/csv', [LaporanController::class, 'exportMutasi'])->name('laporan.mutasi-csv');
        });
    });
});
