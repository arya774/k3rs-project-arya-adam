<!DOCTYPE html>
<html>
<head>
    <title>Hasil Inspeksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f8f9fa; }

        .card { border-radius: 10px; }

        th {
            background-color: #0d6efd;
            color: white;
            text-align: center;
        }

        td { vertical-align: middle; }

        .ya { color: green; font-weight: bold; }
        .tidak { color: red; font-weight: bold; }

        .rekap-box {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        .ttd-box { margin-top: 50px; }

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

        .ttd-label { font-weight: bold; }
    </style>
</head>

<body>

<div class="container mt-4">

    <!-- HEADER -->
    <div class="card shadow-sm p-3">
        <h3 class="text-center mb-3">Hasil Inspeksi K3 RSUD Kota Bogor</h3>

        <div class="row mb-3">
            <div class="col-md-6">
                <p><b>Tanggal:</b> {{ $inspeksi->tanggal ?? '-' }}</p>
                <p><b>Ruangan:</b> {{ $inspeksi->ruangan ?? '-' }}</p>
            </div>

            <div class="col-md-6 text-end">
                <a href="{{ route('inspeksi.cetak', $inspeksi->id) }}"
                   class="btn btn-primary btn-sm" target="_blank">
                    🖨 Cetak PDF
                </a>

                <a href="{{ route('inspeksi.wizard') }}"
                   class="btn btn-secondary btn-sm">
                    ← Kembali
                </a>
            </div>
        </div>

        <!-- TABLE -->
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
                        <th>Foto</th> <!-- 🔥 TAMBAHAN -->
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

                        <!-- 🔥 FOTO -->
                        <td>
                            @if($inspeksi->fotos->count())
                                @foreach($inspeksi->fotos as $foto)
                                    <a href="{{ $foto->url }}" target="_blank">
                                        <img src="{{ $foto->url }}"
                                             width="70"
                                             class="img-thumbnail mb-1">
                                    </a>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data inspeksi</td>
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
                <p>Total</p>
                <h5>{{ $total ?? 0 }}</h5>
            </div>

            <div class="col-md-3">
                <p>YA</p>
                <h5 class="text-success">{{ $ya ?? 0 }}</h5>
            </div>

            <div class="col-md-3">
                <p>TIDAK</p>
                <h5 class="text-danger">{{ $tidak ?? 0 }}</h5>
            </div>

            <div class="col-md-3">
                <p>Persentase</p>
                <h5>{{ number_format($persentase ?? 0, 2) }}%</h5>
            </div>
        </div>
    </div>

    <!-- TTD -->
    <div class="ttd-box row text-center mt-5">

        <div class="col-md-6">
            <p class="ttd-label">Petugas K3RS</p>

            <div class="ttd-img-box">
                @php
                    $ttdK3rs = $inspeksi->paraf_petugas_k3rs ?? null;
                    $pathK3rs = $ttdK3rs ? storage_path('app/public/paraf/'.$ttdK3rs) : null;
                @endphp

                @if($inspeksi->paraf_petugas_k3rs)
    <img src="{{ $inspeksi->paraf_petugas_k3rs }}" class="ttd-img">
@else
    <span class="text-danger">TTD tidak tersedia</span>
@endif
            </div>

            <div class="ttd-line"></div>
            <div class="ttd-name">{{ $inspeksi->nama_petugas_k3rs ?? '-' }}</div>
        </div>

        <div class="col-md-6">
            <p class="ttd-label">Petugas Ruangan</p>

            <div class="ttd-img-box">
                @php
                    $ttdRuangan = $inspeksi->paraf_petugas_ruangan ?? null;
                    $pathRuangan = $ttdRuangan ? storage_path('app/public/paraf/'.$ttdRuangan) : null;
                @endphp

                @if($inspeksi->paraf_petugas_ruangan)
    <img src="{{ $inspeksi->paraf_petugas_ruangan }}" class="ttd-img">
@else
    <span class="text-danger">TTD tidak tersedia</span>
@endif
            </div>

            <div class="ttd-line"></div>
            <div class="ttd-name">{{ $inspeksi->nama_petugas_ruangan ?? '-' }}</div>
        </div>

    </div>

</div>

</body>
</html>
