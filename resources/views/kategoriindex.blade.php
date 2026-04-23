@extends('layouts.app')

@section('title', 'Kategori')

@section('content')

<div class="container mt-4">
    <h3>Data Kategori</h3>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- FORM TAMBAH --}}
    <form action="{{ route('kategori.store') }}" method="POST" class="mb-3">
        @csrf
        <div class="input-group">
            <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori" required>
            <button class="btn btn-primary">Tambah</button>
        </div>
    </form>

    {{-- TABLE --}}
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
        </tr>

        @forelse($kategoris as $i => $k)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $k->nama_kategori }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="2" class="text-center text-muted">Belum ada data</td>
        </tr>
        @endforelse
    </table>
</div>

@endsection