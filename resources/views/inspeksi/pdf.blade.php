<!DOCTYPE html>
<html>
<head>
    <title>Hasil Inspeksi</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 14px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .header {
            margin-bottom: 15px;
        }

        .header p {
            margin: 3px 0;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }

        th, td { 
            border: 1px solid black; 
            padding: 6px; 
        }

        th { 
            background: #f2f2f2; 
            text-align: center;
        }

        .text-center { text-align: center; }
        .text-success { color: green; font-weight: bold; }
        .text-danger { color: red; font-weight: bold; }

        .rekap {
            margin-top: 20px;
        }

        .ttd {
            margin-top: 50px;
            width: 100%;
        }

        .ttd td {
            border: none;
            text-align: center;
            width: 50%;
        }

        .line {
            border-top: 1px solid black;
            width: 200px;
            margin: 5px auto;
        }
    </style>
</head>
<body>

<h2>HASIL INSPEKSI K3RS</h2>
<hr>

<div class="header">
    <p><strong>Tanggal:</strong> {{ $inspeksi->tanggal }}</p>
    <p><strong>Ruangan:</strong> {{ $inspeksi->ruangan }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kategori</th>
            <th>Uraian</th>
            <th>Sub Uraian</th>
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
                @if(strtolower($d->nilai) == 'ya')
                    <span class="text-success">YA</span>
                @else
                    <span class="text-danger">TIDAK</span>
                @endif
            </td>
            <td>{{ $d->catatan ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- REKAP -->
<div class="rekap">
    <h4>Rekap Nilai</h4>
    <p>Total Pertanyaan: <strong>{{ $total }}</strong></p>
    <p>Jumlah YA: <strong style="color:green">{{ $ya }}</strong></p>
    <p>Jumlah TIDAK: <strong style="color:red">{{ $tidak }}</strong></p>

    <h3>
        Persentase: 
        <span style="font-weight:bold;">
            {{ number_format($persentase, 2) }}%
        </span>
    </h3>
</div>

<!-- TANDA TANGAN -->
<table class="ttd">
    <tr>

        <!-- K3RS -->
        <td>
            <strong>Petugas K3RS</strong><br><br>

            @php
                $path_k3rs = public_path('storage/paraf/' . $inspeksi->paraf_petugas_k3rs);
            @endphp

            @if(!empty($inspeksi->paraf_petugas_k3rs) && file_exists($path_k3rs))
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents($path_k3rs)) }}" 
                     style="height:80px;"><br>
            @else
                <br><br>
            @endif

            <div class="line"></div>

            <strong>{{ $inspeksi->nama_petugas_k3rs ?? '-' }}</strong>
        </td>

        <!-- RUANGAN -->
        <td>
            <strong>Petugas Ruangan</strong><br><br>

            @php
                $path_ruangan = public_path('storage/paraf/' . $inspeksi->paraf_petugas_ruangan);
            @endphp

            @if(!empty($inspeksi->paraf_petugas_ruangan) && file_exists($path_ruangan))
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents($path_ruangan)) }}" 
                     style="height:80px;"><br>
            @else
                <br><br>
            @endif

            <div class="line"></div>

            <strong>{{ $inspeksi->nama_petugas_ruangan ?? '-' }}</strong>
        </td>

    </tr>
</table>

</body>
</html>