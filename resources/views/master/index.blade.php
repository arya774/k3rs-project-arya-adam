{{-- resources/views/master/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1 class="mb-4 fw-bold">Master Data</h1>

    {{-- ================= KATEGORI ================= --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            Kategori
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->nama_kategori }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-3">
                                    Tidak ada kategori
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ================= URAIAN ================= --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white">
            Uraian
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kategori</th>
                            <th>Nama Uraian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($uraian as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $item->nama_uraian }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">
                                    Tidak ada uraian
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ================= SUB URAIAN ================= --}}
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Sub Uraian
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kategori</th>
                            <th>Uraian</th>
                            <th>Sub Uraian</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($suburaian as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->uraian->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $item->uraian->nama_uraian ?? '-' }}</td>
                                <td>{{ $item->nama_sub_uraian }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    Tidak ada sub uraian
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>
@endsection