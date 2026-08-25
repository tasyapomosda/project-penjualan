<?php

use Illuminate\Support\Facades\Route;

// Controller pembeli
use App\Http\Controllers\PembeliController;

// Controller admin (folder Admin/)
use App\Http\Controllers\Admin\HarianController;
use App\Http\Controllers\Admin\StokController;
use App\Http\Controllers\Admin\KasController;
use App\Http\Controllers\Admin\HutangController;

// Controller yang TIDAK dipecah (tetap seperti semula)
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;   // dashboard & laporan
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| 1. Halaman Pembeli (Depan / Self-Service)
|--------------------------------------------------------------------------
*/
Route::get('/', [PembeliController::class, 'index']);
Route::post('/beli', [PembeliController::class, 'store'])->name('transaksi.store');

/*
|--------------------------------------------------------------------------
| 2. Autentikasi
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 3. Halaman Admin (Proteksi Auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // ── Dashboard ──────────────────────────────────────────────────────────
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    // ── Transaksi Harian ───────────────────────────────────────────────────
    Route::get('/admin/harian', [HarianController::class, 'index'])->name('admin.harian');
    Route::delete('/admin/transaksi/delete/{id}', [HarianController::class, 'destroy'])->name('admin.delete-transaksi');

    // ── Stok Barang ────────────────────────────────────────────────────────
    Route::get('/admin/stok',           [StokController::class, 'index'])->name('admin.stok');
    Route::post('/admin/stok/store',    [StokController::class, 'store'])->name('admin.stok-store');
    Route::put('/admin/stok/{id}',      [StokController::class, 'update'])->name('admin.stok-update');
    Route::delete('/admin/stok/{id}',   [StokController::class, 'destroy'])->name('admin.stok-delete');

    // ── Rekap Pendapatan / Kas Manual ──────────────────────────────────────
    Route::get('/admin/pendapatan',     [KasController::class, 'index'])->name('admin.pendapatan');
    Route::post('/admin/kas/store',     [KasController::class, 'store'])->name('admin.kas-store');
    Route::put('/admin/kas/{id}',       [KasController::class, 'update'])->name('admin.kas-update');
    Route::delete('/admin/kas/{id}',    [KasController::class, 'destroy'])->name('admin.kas-delete');

    // ── Rekap Hutang ───────────────────────────────────────────────────────
    // ⚠️ Route spesifik (/store dan /{id}/lunas) HARUS di atas wildcard /{id}
    Route::get('/admin/hutang',                     [HutangController::class, 'index'])->name('admin.hutang');
    Route::post('/admin/hutang/store',              [HutangController::class, 'store'])->name('admin.hutang-store');
    Route::put('/admin/hutang/{id}/lunas',          [HutangController::class, 'lunas'])->name('admin.hutang-lunas');
    Route::put('/admin/hutang/{id}',                [HutangController::class, 'update'])->name('admin.hutang-update');
    Route::delete('/admin/hutang/{id}',             [HutangController::class, 'destroy'])->name('admin.hutang-delete');

    // ── Laporan ────────────────────────────────────────────────────────────
    Route::get('/admin/laporan',        [LaporanController::class, 'index'])->name('admin.laporan');
    Route::get('/admin/laporan/excel',  [LaporanController::class, 'exportExcel'])->name('admin.laporan.excel');
    Route::get('/admin/laporan/pdf',    [LaporanController::class, 'exportPdf'])->name('admin.laporan.pdf');
});