<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Inspeksi')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
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
        }
    </style>
</head>

<body>

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

    </div>

</div>

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

</body>
</html>