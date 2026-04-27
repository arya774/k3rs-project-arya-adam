<!DOCTYPE html>
<html>
<head>
    <title>Dashboard INSPEKSI K3</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;
        }

        /* 🔥 SIDEBAR FIXED */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100vh;
            background: #0d6efd;
            color: white;
            padding: 20px;
            overflow-y: auto;
        }

        .sidebar a {
            display: block;
            color: #e2e8f0;
            padding: 10px;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 5px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        /* 🔥 CONTENT GESER */
        .content {
            margin-left: 240px;
            padding: 25px;
        }

        .card-summary {
            border-radius: 12px;
            border: none;
        }

        .percent {
            font-size: 1.8rem;
            font-weight: bold;
        }

        /* 🔥 RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <h5>📋 Menu</h5>

    <a href="{{ route('dashboard') }}" class="active">
        📊 Dashboard
    </a>

    <a href="{{ route('inspeksi.wizard') }}">
        📝 Form Inspeksi
    </a>

    <hr>

    <h6 class="text-white-50">MASTER DATA</h6>

    <a href="{{ route('kategori.index') }}">
        📂 Kategori
    </a>

    <a href="{{ route('uraian.index') }}">
        📄 Uraian
    </a>

    <a href="{{ route('suburaian.index') }}">
        🧩 Sub Uraian
    </a>

    <hr>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-light w-100">
            Logout
        </button>
    </form>

</div>

<!-- CONTENT -->
<div class="content">

    <h3 class="mb-4">Dashboard Inspeksi</h3>

    @php
        $total = $total ?? 0;
        $ya = $ya ?? 0;
        $tidak = $tidak ?? 0;
        $persentase = $persentase ?? 0;
    @endphp

    @if($inspeksis->count() > 0)

    <!-- CARD SUMMARY -->
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card text-white bg-primary card-summary text-center p-3">
                <h6>Total</h6>
                <div class="percent">{{ $total }}</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success card-summary text-center p-3">
                <h6>YA</h6>
                <div class="percent">{{ $ya }}</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-danger card-summary text-center p-3">
                <h6>TIDAK</h6>
                <div class="percent">{{ $tidak }}</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning card-summary text-center p-3">
                <h6>PERSENTASE</h6>
                <div class="percent">{{ number_format($persentase,2) }}%</div>
            </div>
        </div>

    </div>

    @else
    <div class="alert alert-warning">
        Belum ada data inspeksi
    </div>
    @endif

    <!-- TABEL -->
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            Data Inspeksi
        </div>

        <div class="card-body">
            <div class="table-responsive"> <!-- 🔥 biar scroll horizontal kalau sempit -->
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Ruangan</th>
                            <th>Petugas K3RS</th>
                            <th>Petugas Ruangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inspeksis as $i => $item)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->ruangan }}</td>
                            <td>{{ $item->nama_petugas_k3rs }}</td>
                            <td>{{ $item->nama_petugas_ruangan }}</td>
                            <td>
                                <a href="{{ route('inspeksi.hasil', $item->id) }}" class="btn btn-sm btn-info">
                                    Detail
                                </a>

                                <form action="{{ route('inspeksi.destroy', $item->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin mau hapus data ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

</body>
</html>