{{-- resources/views/master/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10">
    <h1 class="text-3xl font-bold mb-6">Master Data</h1>

    {{-- Tampilkan Kategori --}}
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-2">Kategori</h2>
        <table class="w-full border border-gray-300 table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Nama Kategori</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategori as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">{{ $item->id }}</td>
                        <td class="border px-4 py-2">{{ $item->nama }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="border px-4 py-2 text-center text-gray-500">Tidak ada kategori</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tampilkan Uraian --}}
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-2">Uraian</h2>
        <table class="w-full border border-gray-300 table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Nama Uraian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($uraian as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">{{ $item->id }}</td>
                        <td class="border px-4 py-2">{{ $item->nama }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="border px-4 py-2 text-center text-gray-500">Tidak ada uraian</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tampilkan Sub Uraian --}}
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-2">Sub Uraian</h2>
        <table class="w-full border border-gray-300 table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Nama Sub Uraian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suburaian as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">{{ $item->id }}</td>
                        <td class="border px-4 py-2">{{ $item->nama }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="border px-4 py-2 text-center text-gray-500">Tidak ada sub uraian</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection