<!DOCTYPE html>

<html>
<head>
    <title>Laporan Inspeksi</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: #f4f6f9;
    }

    .sidebar {
        position: fixed;
        width: 250px;
        height: 100vh;
        background: linear-gradient(180deg,#0d6efd,#0a58ca);
        color: white;
        padding: 20px;
    }

    .sidebar a {
        display: block;
        color: white;
        padding: 10px;
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 6px;
    }

    .sidebar a:hover {
        background: rgba(255,255,255,0.2);
    }

    .content {
        margin-left: 270px;
        padding: 25px;
    }

    .card {
        border-radius: 12px;
        border: none;
    }

    .table thead {
        background: #0d6efd;
        color: white;
    }
</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">
    <h5>INSPEKSI K3</h5>
    <hr>

<a href="{{ route('dashboard') }}">📊 Dashboard</a>
<a href="{{ route('inspeksi.wizard') }}">📝 Form Inspeksi</a>
<a href="{{ route('laporan.index') }}">📄 Laporan</a>


</div>

<!-- CONTENT -->

<div class="content">

<h3 class="mb-4">Laporan Inspeksi</h3>

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<!-- FILTER -->
<div class="card p-3 mb-3">
    <form method="GET" action="{{ route('laporan.index') }}">
        <div class="row">

            <div class="col-md-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label>Ruangan</label>
                <input type="text" name="ruangan" value="{{ request('ruangan') }}" class="form-control" placeholder="Contoh: ICU / Tulip">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">Filter</button>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('laporan.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>

            <!-- ✅ FIX CETAK TANPA FORM DALAM FORM -->
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('laporan.cetak.ruangan', ['ruangan' => request('ruangan')]) }}"
                   target="_blank"
                   class="btn btn-success w-100">
                    🖨 Cetak
                </a>
            </div>

        </div>
    </form>
</div>

<!-- TABLE -->
<div class="card p-3">

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Ruangan</th>
                    <th>Kategori</th>
                    <th>Uraian</th>
                    <th>Sub Uraian</th>
                    <th>Nilai</th>
                    <th>Catatan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $row)
                <tr>
                    <td>{{ $row->tanggal }}</td>
                    <td>{{ $row->ruangan }}</td>
                    <td>{{ $row->nama_kategori }}</td>
                    <td>{{ $row->nama_uraian }}</td>
                    <td>{{ $row->nama_sub_uraian }}</td>

                    <td>
                        <span class="badge bg-{{ $row->nilai == 'ya' ? 'success' : 'danger' }}">
                            {{ strtoupper($row->nilai) }}
                        </span>
                    </td>

                    <td>{{ $row->catatan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">
                        Tidak ada data inspeksi
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

</div>

</body>
</html>
