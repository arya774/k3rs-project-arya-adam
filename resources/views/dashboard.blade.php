<style>
body {
    background: red !important;
}
</style>
<div class="bg-red-500 text-white p-5">
    TEST MERAH
</div>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
<div class="content-box">
    <h4 class="mb-4">Dashboard Inspeksi</h4>

    <div class="row g-3">
        <div class="col-md-3">
           <div class="card card-custom">
                <h6>Total</h6>
                <h2>1</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-custom bg-green text-center">
                <h6>YA</h6>
                <h2>1</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-custom bg-red text-center">
                <h6>TIDAK</h6>
                <h2>0</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-custom bg-yellow text-center">
                <h6>%</h6>
                <h2>100%</h2>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button class="btn btn-primary btn-custom">Cetak PDF</button>
        <button class="btn btn-success btn-custom">Cetak Excel</button>
    </div>
</div>
    <div class="container">
 <div class="card card-custom">
        <h2>Dashboard</h2>
        <button class="btn">Simpan</button>
    </div>
</div>
<h1 style="color:red;">TES BERHASIL</h1>

<h1 style="color:red;">INI DASHBOARD</h1>
Route::get('/', function () {
    return view('welcome');
});
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<div class="sidebar">
    <div class="p-4 font-bold text-yellow-400">Menu</div>
    <a href="#" class="active">Dashboard</a>
    <a href="#">Form Inspeksi</a>
</div>

<div class="content">
    <h1 class="text-2xl font-bold mb-4">Dashboard Inspeksi</h1>

    <div class="flex gap-5 flex-wrap">
       <div class="card card-custom">
            <div class="card-title">Total</div>
            <div class="card-value">1</div>
        </div>

        <div class="card-box card-green">
            <div class="card-title">YA</div>
            <div class="card-value">1</div>
        </div>
    </div>

    <div class="mt-5">
        <button class="btn-custom btn-blue">Cetak PDF</button>
        <button class="btn-custom btn-green">Cetak Excel</button>
    </div>
</div>
