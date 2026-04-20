<!DOCTYPE html>
<html>
<head>
    <title>Dashboard INSPEKSI K3</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
<<<<<<< HEAD
    :root {
        --primary: #0075f2;
        --success: #16a34a;
        --danger: #dc2626;
        --warning: #facc15;
        --sidebar: #0d6efd;
        --bg: #f1f5f9;
    }

    body {
        background: var(--bg);
    }

    .title-page {
    font-size: 16px;
}

    /* SIDEBAR */
    .sidebar {
        height: 100vh;
        background: var(--sidebar);
        color: white;
        padding: 20px;
    }

    .sidebar h5 { color: #ffffff; }

    .sidebar a {
        color: #cbd5e1;
        text-decoration: none;
        display: block;
        padding: 8px;
        border-radius: 6px;
        transition: 0.2s;
    }

    .sidebar a:hover {
        background: #334155;
    }
=======
        body { 
            background: #f4f6f9; 
            font-family: 'Segoe UI', sans-serif;
        }

        /* 🔥 SIDEBAR SAMA PERSIS DENGAN WIZARD */
        .sidebar {
            height: 100vh;
            background: #0d6efd;
            color: white;
            padding: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 5px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,0.2);
        }

        /* CONTENT */
        .content { 
            padding: 20px; 
        }

        /* CARD */
        .card-summary {
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
>>>>>>> 7e358a622973d0aa0eaf766dbb0612e263544bcc

    .sidebar a.active {
        background: var(--primary);
        color: white !important;
        font-weight: bold;
    }

<<<<<<< HEAD
    /* CARD */
    .card-summary {
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        border: none;
    }
=======
        .card {
            border-radius: 10px;
        }
>>>>>>> 7e358a622973d0aa0eaf766dbb0612e263544bcc

    .bg-primary {
        background: var(--primary) !important;
    }

    .bg-success {
        background: var(--success) !important;
    }

    .bg-danger {
        background: var(--danger) !important;
    }

    .bg-warning {
        background: var(--warning) !important;
        color: black !important;
    }

    .percent {
        font-size: 1.8rem;
        font-weight: bold;
    }

    .content {
        padding: 20px;
    }

    @media(max-width: 768px){
        .sidebar { height: auto; }
    }
</style>
</head>

<body>

<div class="container-fluid">
<div class="row">

<!-- ================= SIDEBAR ================= -->
<div class="col-md-2 sidebar">
    <h5>📋 Menu</h5>

    <a href="{{ route('inspeksi.wizard') }}">
        📝 Form Inspeksi
    </a>

    <a href="#" class="menu-dashboard active">
        📊 Dashboard
    </a>

    <hr>

    <h6>📂 Kategori</h6>

    @forelse($kategoris as $k)
        <a href="#" class="menu-kategori" data-id="{{ $k->id }}">
            • {{ $k->nama_kategori }}
        </a>
    @empty
        <p class="text-light">Tidak ada kategori</p>
    @endforelse

    <hr>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-light text-primary rounded-pill px-4 shadow-sm">
    Logout
</button>
    </form>
</div>

<!-- ================= CONTENT ================= -->
<div class="col-md-9 content">

<h3 class="mb-3">Dashboard Inspeksi</h3>

@if($inspeksi)

<<<<<<< HEAD

<!-- ================= DASHBOARD ================= -->

<div id="dashboard-box">

    <div class="row">

        <div class="col-6 col-md-3">
            <div class="card text-white bg-primary card-summary">
                <div class="card-body text-center">
                    <h6>Total</h6>
                    <div class="percent">{{ $total }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card text-white bg-success card-summary">
                <div class="card-body text-center">
                    <h6>YA</h6>
                    <div class="percent">{{ $ya }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card text-white bg-danger card-summary">
                <div class="card-body text-center">
                    <h6>TIDAK</h6>
                    <div class="percent">{{ $tidak }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card text-dark bg-warning card-summary">
                <div class="card-body text-center">
                    <h6>%</h6>
                    <div class="percent">{{ number_format($persentase,2) }}%</div>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-3">
        <a href="{{ route('inspeksi.cetak', $inspeksi->id) }}" class="btn btn-primary">
            Cetak PDF
        </a>

        <a href="{{ route('inspeksi.export.excel') }}" class="btn btn-success">
            Cetak Excel
        </a>
    </div>

</div>

<hr>

<div id="kategori-wrapper" style="display:none;">

<h5>Data Inspeksi per Kategori</h5>

@foreach($kategoris as $k)

@php
    $dataKategori = $detail->filter(function($d) use ($k) {
        return optional($d->subUraian->uraian->kategori)->id == $k->id;
    });
@endphp

<div class="kategori-box mt-3" id="kategori-{{ $k->id }}" style="display:none;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            {{ $k->nama_kategori }}
        </div>

        <div class="card-body">

            @forelse($dataKategori as $d)

                <div class="border rounded p-2 mb-2 bg-light">
                    <strong>{{ $d->subUraian->nama_sub_uraian ?? '-' }}</strong><br>

                    Nilai:
                    @if($d->nilai == 'ya')
                        <span class="text-success fw-bold">YA</span>
                    @else
                        <span class="text-danger fw-bold">TIDAK</span>
                    @endif

                    <br>
                    Catatan: {{ $d->catatan ?? '-' }}
                </div>

            @empty
                <p class="text-muted">Belum ada data pada kategori ini</p>
            @endforelse

        </div>
    </div>
</div>

@endforeach

</div>

@else
<div class="alert alert-warning">
    Belum ada data inspeksi
</div>
@endif

</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
$(document).ready(function(){

    function resetMenu(){
        $('.sidebar a').removeClass('active');
    }

    $('.menu-dashboard').click(function(e){
        e.preventDefault();
        resetMenu();
        $(this).addClass('active');
        $('#dashboard-box').fadeIn(200);
        $('#kategori-wrapper').hide();
        $('.kategori-box').hide();
    });

    $('.menu-kategori').click(function(e){
        e.preventDefault();

        let id = $(this).data('id');

        resetMenu();
        $(this).addClass('active');

        $('#dashboard-box').hide();
        $('#kategori-wrapper').fadeIn(200);

        $('.kategori-box').hide();
        $('#kategori-' + id).fadeIn(200);
    });

    $('.menu-dashboard').trigger('click');


});
</script>

</body>
</html>
