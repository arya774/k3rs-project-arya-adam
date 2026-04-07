<!DOCTYPE html>
<html>
<head>
    <title>Dashboard INSPEKSI K3</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f4f6f9; }

        .sidebar {
            height: 100vh;
            background: #343a40;
            color: white;
            padding: 20px;
        }

        .sidebar h5 { color: #ffc107; }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 8px;
            border-radius: 5px;
            transition: 0.2s;
        }

        .sidebar a:hover { background: #495057; }

        .sidebar a.active {
            background: #ffc107;
            color: black !important;
            font-weight: bold;
        }

        .card-summary {
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .percent {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .content { padding: 20px; }

        @media(max-width: 768px){
            .sidebar { height: auto; }
        }
    </style>
</head>

<body>

<div class="container-fluid">
<div class="row">

<!-- ================= SIDEBAR ================= -->
<div class="col-md-3 sidebar">
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
</div>

<!-- ================= CONTENT ================= -->
<div class="col-md-9 content">

<h3 class="mb-3">Dashboard Inspeksi</h3>

@if($inspeksi)

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
            
            <a href="/inspeksi/export-excel" class="btn btn-success">
    Cetak Excel
</a>
        </a>
    </div>

</div>

<hr>

<!-- ================= KATEGORI ================= -->
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
        <div class="card-header bg-dark text-white">
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

    // DASHBOARD
    $('.menu-dashboard').click(function(e){
        e.preventDefault();

        resetMenu();
        $(this).addClass('active');

        $('#dashboard-box').fadeIn(200);
        $('#kategori-wrapper').hide();
        $('.kategori-box').hide();
    });

    // KATEGORI
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

    // DEFAULT
    $('.menu-dashboard').trigger('click');

});
</script>

</body>
</html>
