@extends('layouts.app')

@section('title', 'Data Uraian')

@section('content')

<div class="card p-4 mb-3">

    <h4 class="mb-3">Data Uraian</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('uraian.store') }}" method="POST">
        @csrf

        <div class="row g-2">

            <div class="col-md-4">
                <select name="kategori_id" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <input type="text" name="nama_uraian" class="form-control" placeholder="Nama Uraian" required>
            </div>

            <div class="col-md-2">
                <button class="btn btn-success w-100">Tambah</button>
            </div>

        </div>
    </form>

</div>

<div class="card p-4">

    <table class="table table-hover">

        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Uraian</th>
            </tr>
        </thead>

        <tbody>
        @foreach($uraians as $i => $u)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $u->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $u->nama_uraian }}</td>
            </tr>
        @endforeach
        </tbody>

    </table>

</div>

@endsection