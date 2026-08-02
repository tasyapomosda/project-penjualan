<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanController;

// ─── 1. Halaman Pembeli (Depan / Self-Service) ────────────────────────────────
Route::get('/', [TransaksiController::class, 'index']);
Route::post('/beli', [TransaksiController::class, 'store'])->name('transaksi.store');

// ─── 2. Halaman Login & Logout ────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── 3. Halaman Admin (Proteksi Auth) ─────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // ── Dashboard ──────────────────────────────────────────────────────────────
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    // ── Transaksi Harian ───────────────────────────────────────────────────────
    Route::get('/admin/harian', [TransaksiController::class, 'rekapHarian'])->name('admin.harian');
    Route::delete('/admin/transaksi/delete/{id}', [TransaksiController::class, 'deleteTransaksi'])->name('admin.delete-transaksi');

    // ── Stok Barang ────────────────────────────────────────────────────────────
    Route::get('/admin/stok', [TransaksiController::class, 'stok'])->name('admin.stok');
    Route::post('/admin/stok/store', [AdminController::class, 'storeProduct'])->name('admin.stok-store');
    Route::put('/admin/stok/{id}', [AdminController::class, 'updateProduct'])->name('admin.stok-update');
    Route::delete('/admin/stok/{id}', [AdminController::class, 'deleteProduct'])->name('admin.stok-delete');


    // ── Rekap Pendapatan / Kas Manual ──────────────────────────────────────────
    Route::get('/admin/pendapatan', [AdminController::class, 'pendapatan'])->name('admin.pendapatan');
    Route::post('/admin/kas/store', [AdminController::class, 'storeKas'])->name('admin.kas-store');
    Route::put('/admin/kas/{id}', [AdminController::class, 'kasUpdate'])->name('admin.kas-update');
    Route::delete('/admin/kas/{id}', [AdminController::class, 'kasDelete'])->name('admin.kas-delete');

    // ── Rekap Hutang ───────────────────────────────────────────────────────────
    Route::get('/admin/hutang', [AdminController::class, 'hutang'])->name('admin.hutang');

    // ⚠️ PENTING: route spesifik /store dan /{id}/lunas harus di atas /{id}
    // supaya Laravel tidak salah tangkap 'store' atau 'lunas' sebagai {id}
    Route::post('/admin/hutang/store', [AdminController::class, 'hutangStore'])->name('admin.hutang-store');
    Route::put('/admin/hutang/{id}/lunas', [AdminController::class, 'hutangLunas'])->name('admin.hutang-lunas');

    // Route dengan wildcard {id} selalu di bawah
    Route::put('/admin/hutang/{id}', [AdminController::class, 'hutangUpdate'])->name('admin.hutang-update');
    Route::delete('/admin/hutang/{id}', [AdminController::class, 'hutangDelete'])->name('admin.hutang-delete');

    // ── Laporan ────────────────────────────────────────────────────────────────
    Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
    Route::get('/admin/laporan/excel', [LaporanController::class, 'exportExcel'])->name('admin.laporan.excel');
    Route::get('/admin/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('admin.laporan.pdf');
});