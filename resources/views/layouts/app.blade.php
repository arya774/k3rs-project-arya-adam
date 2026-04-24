<!DOCTYPE html>
<html lang="en">
<head>
<<<<<<< HEAD
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
=======
>>>>>>> 4aa0e7687ca7b8264742e1bd6a4c2633dd11a429
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Inspeksi')</title>

<<<<<<< HEAD
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
=======
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
>>>>>>> 4aa0e7687ca7b8264742e1bd6a4c2633dd11a429
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
<<<<<<< HEAD
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
=======
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background: #f4f6f9;
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #0d6efd;
            padding: 15px;
            overflow-y: auto;
        }

        /* CONTENT */
        .main-wrapper {
            margin-left: 240px;
            min-height: 100vh;
        }

        /* MENU */
        .menu-link {
            display: block;
            color: white;
            text-decoration: none;
            padding: 6px 0;
            transition: 0.2s;
        }

        .menu-link:hover {
            opacity: 0.7;
            padding-left: 5px;
        }

        .active-menu {
            font-weight: bold;
        }

        /* CARD LOOK */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
>>>>>>> 4aa0e7687ca7b8264742e1bd6a4c2633dd11a429
        }
    </style>
</head>

<body>
<<<<<<< HEAD

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
=======

<!-- SIDEBAR -->
<div class="sidebar text-white">

    <!-- LOGO -->
    <div class="text-center mb-4">
        <img src="{{ asset('images/logo_rsud.png') }}" width="60">
        <div class="mt-2 fw-bold">RSUD Kota Bogor</div>
    </div>

    <hr class="text-white">

    <h6 class="text-white-50">MENU</h6>

    <a href="/dashboard" class="menu-link">
        📊 Dashboard
    </a>

    {{-- FORM INSPEKSI --}}
    @if(Route::has('inspeksi.create'))
        <a href="{{ route('inspeksi.create') }}" class="menu-link">
            📝 Form Inspeksi
        </a>
    @endif

    <hr class="text-white">

    {{-- MASTER DATA --}}
    <p style="cursor:pointer;" onclick="toggleMenu()">
        📁 Master Data
    </p>

    <div id="masterMenu" style="display:none; margin-left:10px;">

        <a href="{{ route('kategori.index') }}"
           class="menu-link {{ request()->is('kategori*') ? 'active-menu' : '' }}">
            - Kategori
        </a>

        <a href="{{ route('uraian.index') }}"
           class="menu-link {{ request()->is('uraian*') ? 'active-menu' : '' }}">
            - Uraian
        </a>

        <a href="{{ route('suburaian.index') }}"
           class="menu-link {{ request()->is('suburaian*') ? 'active-menu' : '' }}">
            - Sub Uraian
        </a>
>>>>>>> 4aa0e7687ca7b8264742e1bd6a4c2633dd11a429

    </div>

</div>

<<<<<<< HEAD
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

=======
<!-- MAIN CONTENT -->
<div class="main-wrapper">

    <!-- TOP NAVBAR -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <span class="navbar-brand">Aplikasi Inspeksi</span>
        </div>
    </nav>

    <!-- CONTENT -->
    <div class="p-4">

        {{-- ALERT GLOBAL --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- PAGE CONTENT --}}
        @yield('content')

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleMenu() {
    const menu = document.getElementById("masterMenu");
    if (!menu) return;

    menu.style.display = (menu.style.display === "block") ? "none" : "block";
}
</script>

>>>>>>> 4aa0e7687ca7b8264742e1bd6a4c2633dd11a429
</body>
</html>