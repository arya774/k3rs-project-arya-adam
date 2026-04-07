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
            margin-top: 40px;
        }

        .ttd-box div {
            text-align: center;
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

        <div class="row">
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
    <div class="ttd-box row mt-4">
        <div class="col-md-6">
            <p>Petugas K3RS</p>
            <br><br><br>
            <strong>{{ $inspeksi->nama_petugas_k3rs ?? '-' }}</strong><br>
            <small>{{ $inspeksi->paraf_petugas_k3rs ?? 'Paraf' }}</small>
        </div>

        <div class="col-md-6">
            <p>Petugas Ruangan</p>
            <br><br><br>
            <strong>{{ $inspeksi->nama_petugas_ruangan ?? '-' }}</strong><br>
            <small>{{ $inspeksi->paraf_petugas_ruangan ?? 'Paraf' }}</small>
        </div>
    </div>

</div>

</body>
</html>