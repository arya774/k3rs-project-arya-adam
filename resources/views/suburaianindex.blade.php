@extends('layouts.app')

@section('title', 'Data Sub Uraian')

@section('content')

<div class="card p-4 mb-3">

    <h4 class="mb-3">Data Sub Uraian</h4>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- FORM --}}
    <form action="{{ route('suburaian.store') }}" method="POST">
        @csrf

        <div class="row g-2">

            <div class="col-md-4">
                <select name="uraian_id" class="form-select" required>
                    <option value="">-- Pilih Uraian --</option>

                    @foreach($uraians as $u)
                        <option value="{{ $u->id }}">
                            {{ $u->kategori->nama_kategori ?? '-' }} → {{ $u->nama_uraian }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-6">
                <input type="text"
                       name="nama_sub_uraian"
                       class="form-control"
                       placeholder="Nama Sub Uraian"
                       required>
            </div>

            <div class="col-md-2">
                <button class="btn btn-warning w-100">
                    Tambah
                </button>
            </div>

        </div>
    </form>

</div>

<div class="card p-4">

    <table class="table table-hover align-middle">

        <thead>
            <tr>
                <th width="60">No</th>
                <th>Kategori</th>
                <th>Uraian</th>
                <th>Sub Uraian</th>
            </tr>
        </thead>

        <tbody>

        @forelse($suburaians as $i => $s)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $s->uraian->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $s->uraian->nama_uraian ?? '-' }}</td>
                <td>{{ $s->nama_sub_uraian }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">
                    Belum ada data sub uraian
                </td>
            </tr>
        @endforelse

        </tbody>

    </table>

</div>

@endsection