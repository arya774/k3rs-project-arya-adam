@vite(['resources/css/app.css', 'resources/js/app.js'])

<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Inspeksi')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Inspeksi</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="{{ route('kategori.index') }}">Kategori</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('inspeksi.create') }}">Inspeksi</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <!-- LOGO -->
        <div class="logo-sidebar">
            <img src="{{ asset('images/logo_rsud.png') }}" class="logo-img">
            <div class="logo-text">RSUD Kota Bogor</div>
        </div>

        <!-- MENU -->
        <a href="#">Dashboard</a>
        <a href="{{ route('inspeksi.create') }}">Form Inspeksi</a>
        <a href="{{ route('kategori.index') }}">Kategori</a>

    </div>

    <!-- CONTENT -->
    <div class="main-content">

        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-3">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Inspeksi</a>
            </div>
        </nav>

        <div class="container-fluid">
            @yield('content')
        </div>

    </div>

</div>

</body>
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
