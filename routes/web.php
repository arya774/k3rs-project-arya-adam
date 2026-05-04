<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InspeksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UraianController;
use App\Http\Controllers\SubUraianController;
use App\Models\SubUraian;
use App\Models\Uraian;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', fn () => view('auth.login'))->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.process');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| AUTH AREA
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/', fn () => redirect()->route('dashboard'));

    Route::get('/dashboard', [InspeksiController::class, 'dashboard'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | MASTER DATA
    |--------------------------------------------------------------------------
    */
    Route::resource('kategori', KategoriController::class);
    Route::resource('uraian', UraianController::class);
    Route::resource('suburaian', SubUraianController::class);

    /*
    |--------------------------------------------------------------------------
    | 🔥 API UNTUK AJAX
    |--------------------------------------------------------------------------
    */
    Route::get('/inspeksi/get-uraian-all', fn () => Uraian::all());
    Route::get('/inspeksi/get-sub-all', fn () => SubUraian::with('uraian')->latest()->get());

    /*
    |--------------------------------------------------------------------------
    | INSPEKSI
    |--------------------------------------------------------------------------
    */
    Route::prefix('inspeksi')->name('inspeksi.')->group(function () {

        // ✅ TAMBAHAN (INI YANG BIKIN ERROR LO HILANG)
        Route::get('/', [InspeksiController::class, 'index'])
            ->name('index');

        Route::get('/wizard', [InspeksiController::class, 'wizard'])
            ->name('wizard');

        Route::post('/', [InspeksiController::class, 'store'])
            ->name('store');

        Route::get('/hasil/{id}', [InspeksiController::class, 'hasil'])
            ->name('hasil');

        Route::get('/cetak/{id}', [InspeksiController::class, 'cetak'])
            ->name('cetak');

        Route::get('/export-excel', [InspeksiController::class, 'exportExcel'])
            ->name('export');

        Route::delete('/{id}', [InspeksiController::class, 'destroy'])
            ->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | LAPORAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('laporan')->name('laporan.')->group(function () {

        Route::get('/', [LaporanController::class, 'index'])
            ->name('index');

        Route::get('/cetak-ruangan', [LaporanController::class, 'cetakPerRuangan'])
            ->name('cetak.ruangan');
    });

});