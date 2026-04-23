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

        .sidebar {
            height: 100vh;
            background: #0d6efd;
            color: white;
            padding: 20px;
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

        .content {
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
    </style>
</head>

<body>

<div class="container-fluid">
<div class="row">

    <!-- SIDEBAR -->
    <div class="col-md-2 sidebar">

        <h5>📋 Menu</h5>

        <!-- FIX: pastikan route benar -->
        <a href="{{ route('dashboard') }}" class="active">
            📊 Dashboard
        </a>

        <a href="{{ route('inspeksi.wizard') }}">
            📝 Form Inspeksi
        </a>

        <hr>

        <h6 class="text-white-50">MASTER DATA</h6>

        <!-- FIX: gunakan route resource -->
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
    <div class="col-md-10 content">

        <h3 class="mb-4">Dashboard Inspeksi</h3>

        @php
            // SAFETY supaya tidak undefined error
            $inspeksi = $inspeksi ?? null;
            $total = $total ?? 0;
            $ya = $ya ?? 0;
            $tidak = $tidak ?? 0;
            $persentase = $persentase ?? 0;
        @endphp

        @if($inspeksi)

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

    </div>
</div>
</div>

</body>
</html>