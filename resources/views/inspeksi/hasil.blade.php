<!DOCTYPE html>
<html>
<head>
    <title>Hasil Inspeksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .card {
            border-radius: 10px;
        }

        table {
            width: 100%;
        }

        th {
            background-color: #0d6efd;
            color: white;
            text-align: center;
        }

        td {
            vertical-align: middle;
        }

        .ya {
            color: green;
            font-weight: bold;
        }

        .tidak {
            color: red;
            font-weight: bold;
        }

        .rekap-box {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        .ttd-box {
            margin-top: 50px;
        }

        .ttd-img-box {
            height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ttd-img {
            max-height: 90px;
            object-fit: contain;
        }

        .ttd-line {
            border-top: 1px solid #000;
            width: 60%;
            margin: 10px auto;
        }

        .ttd-name {
            margin-top: 5px;
            font-weight: bold;
        }

        .ttd-label {
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="container mt-4">

    <div class="card shadow-sm p-3">
        <h3 class="text-center mb-3">Hasil Inspeksi K3 RSUD Kota Bogor</h3>

        <div class="row mb-3">
            <div class="col-md-6">
                <p><b>Tanggal:</b> {{ $inspeksi->tanggal }}</p>
                <p><b>Ruangan:</b> {{ $inspeksi->ruangan }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('inspeksi.cetak', $inspeksi->id) }}" class="btn btn-primary btn-sm" target="_blank">
                    🖨 Cetak PDF
                </a>
                <a href="{{ route('inspeksi.wizard') }}" class="btn btn-secondary btn-sm">
                    ← Kembali
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Uraian</th>
                        <th>Sub-Uraian</th>
                        <th>Nilai</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($detail as $i => $d)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $d->subUraian->uraian->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $d->subUraian->uraian->nama_uraian ?? '-' }}</td>
                            <td>{{ $d->subUraian->nama_sub_uraian ?? '-' }}</td>
                            <td class="text-center">
                                @if(strtolower($d->nilai) === 'ya')
                                    <span class="ya">YA</span>
                                @else
                                    <span class="tidak">TIDAK</span>
                                @endif
                            </td>
                            <td>{{ $d->catatan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data inspeksi</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <!-- REKAP -->
    <div class="rekap-box mt-3">
        <h4>Rekap Nilai</h4>

        <div class="row text-center">
            <div class="col-md-3">
                <p>Total Pertanyaan</p>
                <h5>{{ $total }}</h5>
            </div>

            <div class="col-md-3">
                <p>Jumlah Ya</p>
                <h5 class="text-success">{{ $ya }}</h5>
            </div>

            <div class="col-md-3">
                <p>Jumlah Tidak</p>
                <h5 class="text-danger">{{ $tidak }}</h5>
            </div>

            <div class="col-md-3">
                <p>Persentase</p>
                <h5>{{ number_format($persentase, 2) }}%</h5>
            </div>
        </div>
    </div>

  <!-- TANDA TANGAN -->
<div class="ttd-box row text-center">

    <!-- K3RS -->
    <div class="col-md-6">
        <p class="ttd-label">Petugas K3RS</p>

        <div class="ttd-img-box">
            @php
                $file_k3rs = public_path('storage/paraf/' . trim($inspeksi->paraf_petugas_k3rs));
            @endphp

            @if(!empty($inspeksi->paraf_petugas_k3rs) && file_exists($file_k3rs))
                <img src="{{ asset('storage/paraf/' . trim($inspeksi->paraf_petugas_k3rs)) }}" class="ttd-img">
            @else
                <span class="text-danger">TTD tidak ditemukan</span>
                <br>
                <small>{{ $inspeksi->paraf_petugas_k3rs }}</small>
            @endif
        </div>

        <div class="ttd-line"></div>

        <div class="ttd-name">
            {{ $inspeksi->nama_petugas_k3rs ?? '-' }}
        </div>
    </div>

    <!-- RUANGAN -->
    <div class="col-md-6">
        <p class="ttd-label">Petugas Ruangan</p>

        <div class="ttd-img-box">
            @php
                $file_ruangan = public_path('storage/paraf/' . trim($inspeksi->paraf_petugas_ruangan));
            @endphp

            @if(!empty($inspeksi->paraf_petugas_ruangan) && file_exists($file_ruangan))
                <img src="{{ asset('storage/paraf/' . trim($inspeksi->paraf_petugas_ruangan)) }}" class="ttd-img">
            @else
                <span class="text-danger">TTD tidak ditemukan</span>
                <br>
                <small>{{ $inspeksi->paraf_petugas_ruangan }}</small>
            @endif
        </div>

        <div class="ttd-line"></div>

        <div class="ttd-name">
            {{ $inspeksi->nama_petugas_ruangan ?? '-' }}
        </div>
    </div>

</div>