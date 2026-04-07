<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InspeksiController;
use App\Http\Controllers\LaporanController;

Route::get('/inspeksi/export-excel', [InspeksiController::class, 'exportExcel']);
// ================================
// HALAMAN UTAMA → WIZARD
// ================================
Route::get('/', [InspeksiController::class, 'wizard'])->name('inspeksi.wizard');

// ================================
// DASHBOARD INSPEKSI (Mobile & Web)
// ================================
Route::get('/inspeksi/dashboard', [InspeksiController::class, 'dashboard'])
    ->name('inspeksi.dashboard');

// ================================
// INSPEKSI & MASTER DATA
// ================================
Route::prefix('inspeksi')->group(function () {

    // WIZARD
    Route::get('/wizard', [InspeksiController::class, 'wizard'])
        ->name('inspeksi.wizard');

    // MASTER DATA (kategori, uraian, suburaian)
    Route::post('/master/{type}', [InspeksiController::class, 'storeMasterData'])
        ->where('type', 'kategori|uraian|suburaian')
        ->name('master.store');

    // SIMPAN INSPEKSI
    Route::post('/store-inspeksi', [InspeksiController::class, 'storeInspeksi'])
        ->name('inspeksi.store');

    // HASIL INSPEKSI
    Route::get('/hasil/{id}', [InspeksiController::class, 'hasil'])
        ->name('inspeksi.hasil');

    // CETAK PDF
    Route::get('/cetak/{id}', [InspeksiController::class, 'cetak'])
        ->name('inspeksi.cetak');
});

// ================================
// LAPORAN INSPEKSI
// ================================
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
