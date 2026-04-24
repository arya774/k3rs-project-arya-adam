<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InspeksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UraianController;
<<<<<<< HEAD
use App\Http\Controllers\MasterDataController;

Route::middleware('auth')->prefix('inspeksi')->group(function () {
    Route::get('/inspeksi/kategori', function () {
    return redirect('/inspeksi/master-data');
});

Route::get('/inspeksi/uraian', function () {
    return redirect('/inspeksi/master-data');
});

Route::get('/inspeksi/sub-uraian', function () {
    return redirect('/inspeksi/master-data');
});

    Route::get('/master-data', [MasterDataController::class, 'index'])->name('master.data');

    Route::post('/master-data/kategori', [MasterDataController::class, 'storeKategori'])->name('master.kategori');

    Route::post('/master-data/uraian', [MasterDataController::class, 'storeUraian'])->name('master.uraian');

    Route::post('/master-data/sub-uraian', [MasterDataController::class, 'storeSubUraian'])->name('master.sub');

    Route::get('/inspeksi/get-uraian/{id}', [MasterDataController::class, 'getUraian']);

    Route::post('/master-data/kategori', [MasterDataController::class, 'storeKategori'])->name('master.kategori');

    Route::post('/master-data/uraian', [MasterDataController::class, 'storeUraian'])->name('master.uraian');

    Route::post('/master-data/sub-uraian', [MasterDataController::class, 'storeSubUraian'])->name('master.sub');

    // AJAX
    Route::get('/get-uraian/{id}', [MasterDataController::class, 'getUraian']);
});
=======
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SubUraianController;
>>>>>>> 4aa0e7687ca7b8264742e1bd6a4c2633dd11a429

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/


<<<<<<< HEAD
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/get-uraian/{id}', [InspeksiController::class, 'getUraian']);
Route::delete('/suburaian/{id}', [InspeksiController::class, 'deleteSubUraian']);
Route::post('/uraian', [UraianController::class, 'store'])->name('uraian.store');
Route::get('/get-sub-all', [InspeksiController::class, 'getSubAll']);
Route::delete('/inspeksi/kategori-delete/{id}', [InspeksiController::class, 'deleteKategori']);
Route::get('/inspeksi/get-sub-all', [InspeksiController::class, 'getSubAll']);
Route::post('/kategori', [InspeksiController::class, 'storeKategori']);
Route::prefix('inspeksi')->group(function () {

Route::get('/master-data', [MasterDataController::class, 'index'])->name('master.data');
Route::post('/master-data/kategori', [MasterDataController::class, 'storeKategori']);
Route::post('/master-data/uraian', [MasterDataController::class, 'storeUraian']);
Route::post('/master-data/sub-uraian', [MasterDataController::class, 'storeSubUraian']);

});

    Route::post('/uraian', [InspeksiController::class, 'storeUraian']);
    Route::post('/sub-uraian', [InspeksiController::class, 'storeSubUraian']);



// 🔒 Halaman login (hanya untuk guest / belum login)
=======
>>>>>>> 4aa0e7687ca7b8264742e1bd6a4c2633dd11a429
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