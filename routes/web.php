<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InspeksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| AUTH (LOGIN)
|--------------------------------------------------------------------------
*/

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/get-uraian/{id}', [InspeksiController::class, 'getUraian']);


// 🔒 Halaman login (hanya untuk guest / belum login)
Route::middleware('guest')->group(function () {

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.process');
});

// 🔓 Logout (harus sudah login)
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| ROUTE YANG HARUS LOGIN DULU
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // 🏠 Home → langsung ke dashboard
    Route::get('/', function () {
        return redirect()->route('inspeksi.dashboard');
    })->name('home');

    Route::prefix('inspeksi')->name('inspeksi.')->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [InspeksiController::class, 'dashboard'])
            ->name('dashboard');

        // WIZARD
        Route::get('/wizard', [InspeksiController::class, 'wizard'])
            ->name('wizard');

        // SIMPAN
        Route::post('/store', [InspeksiController::class, 'storeInspeksi'])
            ->name('store');

        // MASTER DATA
        Route::post('/master/{type}', [InspeksiController::class, 'storeMasterData'])
            ->where('type', 'kategori|uraian|suburaian')
            ->name('master.store');

        // HASIL
        Route::get('/hasil/{id}', [InspeksiController::class, 'hasil'])
            ->name('hasil');

        // CETAK
        Route::get('/cetak/{id}', [InspeksiController::class, 'cetak'])
            ->name('cetak');

        // EXPORT
        Route::get('/export-excel', [InspeksiController::class, 'exportExcel'])
            ->name('export.excel');
    });

    // LAPORAN
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');
});


/*
|--------------------------------------------------------------------------
| FALLBACK
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
