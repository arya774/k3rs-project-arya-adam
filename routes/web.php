<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InspeksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UraianController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SubUraianController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/


Route::middleware('guest')->group(function () {

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.process');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED AREA
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------
    | ROOT
    |--------------------------
    */
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    /*
    |--------------------------
    | DASHBOARD (FIX UTAMA)
    |--------------------------
    */
    Route::get('/dashboard', [InspeksiController::class, 'dashboard'])
        ->name('dashboard');

    /*
    |--------------------------
    | MASTER DATA (RESOURCE)
    |--------------------------
    */
    Route::resource('kategori', KategoriController::class);
    Route::resource('uraian', UraianController::class);
    Route::resource('suburaian', SubUraianController::class);

    /*
    |--------------------------
    | INSPEKSI MODULE
    |--------------------------
    */
    Route::prefix('inspeksi')->name('inspeksi.')->group(function () {

        Route::get('/wizard', [InspeksiController::class, 'wizard'])
            ->name('wizard');

        Route::post('/', [InspeksiController::class, 'storeInspeksi'])
            ->name('store');

        Route::get('/hasil/{id}', [InspeksiController::class, 'hasil'])
            ->name('hasil');

        Route::get('/cetak/{id}', [InspeksiController::class, 'cetak'])
            ->name('cetak');

        Route::get('/export-excel', [InspeksiController::class, 'exportExcel'])
            ->name('export');
    });

    /*
    |--------------------------
    | LAPORAN
    |--------------------------
    */
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');
});

/*
|--------------------------------------------------------------------------
| FALLBACK
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    abort(404);
});