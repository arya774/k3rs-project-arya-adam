@vite(['resources/css/app.css', 'resources/js/app.js'])

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
body {
    margin: 0;
    overflow-x: hidden;
}

/* SIDEBAR FIX */
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto;
}

/* CONTENT GESER KE KANAN */
.content {
    margin-left: 250px;
    min-height: 100vh;
}

/* BIAR SCROLL HALUS */
.content-inner {
    padding: 20px;
}
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Inspeksi')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(180deg, #0d6efd, #0a58ca);
        }

        .sidebar a {
            display: block;
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.2s;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.2);
        }

        .active-menu {
            background: white !important;
            color: #0d6efd !important;
            font-weight: bold;
        }

        .content-area {
            flex: 1;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar bg-primary text-white p-3">

    <h5>INSPEKSI K3</h5>
    <hr>

    <a href="{{ route('inspeksi.dashboard') }}"
       class="nav-link text-white {{ request()->is('inspeksi/dashboard') ? 'fw-bold bg-light text-primary rounded' : '' }}">
        Dashboard
    </a>

    <a href="{{ route('inspeksi.wizard') }}"
       class="nav-link text-white {{ request()->is('inspeksi/wizard') ? 'fw-bold bg-light text-primary rounded' : '' }}">
        Form Inspeksi
    </a>

    <hr>

    <strong>Master Data</strong>

    <a href="/inspeksi/kategori"
       class="nav-link text-white {{ request()->is('inspeksi/kategori') ? 'fw-bold bg-light text-primary rounded' : '' }}">
        • Kategori
    </a>

    <a href="/inspeksi/uraian"
       class="nav-link text-white {{ request()->is('inspeksi/uraian') ? 'fw-bold bg-light text-primary rounded' : '' }}">
        • Uraian
    </a>

    <a href="/inspeksi/sub-uraian"
       class="nav-link text-white {{ request()->is('inspeksi/sub-uraian') ? 'fw-bold bg-light text-primary rounded' : '' }}">
        • Sub Uraian
    </a>

</div>

<!-- CONTENT -->
<div class="content">

    <nav class="navbar bg-white shadow-sm">
        <div class="container-fluid">
            <span class="navbar-brand">Aplikasi Inspeksi K3</span>
        </div>
    </nav>

    <div class="content-inner">
        @yield('content')
    </div>

</div>

</body>

    <!-- CONTENT -->
    <div class="content-area">

        <!-- TOPBAR -->
        <nav class="navbar bg-white shadow-sm px-3">
            <span class="navbar-brand mb-0 h5">
                Aplikasi Inspeksi K3
            </span>
        </nav>

        <!-- MAIN CONTENT -->
        <div class="p-4">
            @yield('content')
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
