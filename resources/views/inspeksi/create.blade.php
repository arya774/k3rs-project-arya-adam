<!DOCTYPE html>
<html>
<head>
    <title>Form Inspeksi K3RS + Master Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">

    <h2>Master Data</h2>

    {{-- Tambah Kategori --}}
    <form action="{{ route('kategori.store') }}" method="POST" class="mb-3">@csrf
        <div class="input-group mb-2">
            <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori" required>
            <button class="btn btn-primary" type="submit">Tambah Kategori</button>
        </div>
    </form>

    {{-- Tambah Uraian --}}
    <form action="{{ route('uraian.store') }}" method="POST" class="mb-3">@csrf
        <div class="input-group mb-2">
            <select name="kategori_id" class="form-select" required>
                <option value="">Pilih Kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
            <input type="text" name="nama_uraian" class="form-control" placeholder="Nama Uraian" required>
            <button class="btn btn-primary" type="submit">Tambah Uraian</button>
        </div>
    </form>

    {{-- Tambah Sub-Uraian --}}
    <form action="{{ route('suburaian.store') }}" method="POST" class="mb-3">@csrf
        <div class="input-group mb-2">
            <select name="uraian_id" class="form-select" required>
                <option value="">Pilih Uraian</option>
                @foreach($kategoris as $k)
                    @foreach($k->uraian as $u)
                        <option value="{{ $u->id }}">{{ $k->nama_kategori }} → {{ $u->nama_uraian }}</option>
                    @endforeach
                @endforeach
            </select>
            <input type="text" name="nama_sub_uraian" class="form-control" placeholder="Nama Sub-Uraian" required>
            <button class="btn btn-primary" type="submit">Tambah Sub-Uraian</button>
        </div>
    </form>

    <hr>
    <h2>Form Inspeksi</h2>

    <form action="{{ route('inspeksi.store') }}" method="POST">@csrf
        @foreach($kategoris as $kategori)
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">{{ $kategori->nama_kategori }}</div>
                <div class="card-body">
                    @foreach($kategori->uraian as $uraian)
                        <h5>{{ $uraian->nama_uraian }}</h5>
                        @foreach($uraian->suburaian as $sub)
                            <div class="form-check form-check-inline ms-4">
                                <input class="form-check-input" type="radio" name="nilai[{{ $sub->id }}]" id="ya-{{ $sub->id }}" value="Ya" required>
                                <label class="form-check-label" for="ya-{{ $sub->id }}">Ya</label>

                                <input class="form-check-input" type="radio" name="nilai[{{ $sub->id }}]" id="tidak-{{ $sub->id }}" value="Tidak">
                                <label class="form-check-label" for="tidak-{{ $sub->id }}">Tidak</label>

                                <span class="ms-2">{{ $sub->nama_sub_uraian }}</span>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endforeach
        <button type="submit" class="btn btn-success">Simpan Inspeksi</button>
    </form>
    <h2>Form Inspeksi</h2>

<form action="{{ route('inspeksi.store') }}" method="POST">@csrf

    {{-- DATA UMUM --}}
    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Ruangan</label>
        <input type="text" name="ruangan" class="form-control" required>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label>Nama Petugas K3RS</label>
            <input type="text" name="nama_petugas_k3rs" class="form-control" required>

            <label class="mt-2">Paraf K3RS</label>
            <canvas id="signature-pad-k3rs" style="border:1px solid #000;width:100%;height:150px;"></canvas>
            <button type="button" onclick="clearK3rs()">Hapus</button>
            <input type="hidden" name="paraf_petugas_k3rs" id="paraf_petugas_k3rs">
        </div>

        <div class="col-md-6">
            <label>Nama Petugas Ruangan</label>
            <input type="text" name="nama_petugas_ruangan" class="form-control" required>

            <label class="mt-2">Paraf Ruangan</label>
            <canvas id="signature-pad-ruangan" style="border:1px solid #000;width:100%;height:150px;"></canvas>
            <button type="button" onclick="clearRuangan()">Hapus</button>
            <input type="hidden" name="paraf_petugas_ruangan" id="paraf_petugas_ruangan">
        </div>
    </div>

    <hr>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    const canvasK3rs = document.getElementById('signature-pad-k3rs');
    const canvasRuangan = document.getElementById('signature-pad-ruangan');

    const padK3rs = new SignaturePad(canvasK3rs);
    const padRuangan = new SignaturePad(canvasRuangan);

    function clearK3rs() {
        padK3rs.clear();
    }

    function clearRuangan() {
        padRuangan.clear();
    }

    document.querySelector('form').addEventListener('submit', function () {
        if (!padK3rs.isEmpty()) {
            document.getElementById('paraf_petugas_k3rs').value = padK3rs.toDataURL();
        }

        if (!padRuangan.isEmpty()) {
            document.getElementById('paraf_petugas_ruangan').value = padRuangan.toDataURL();
        }
    });
</script>
</body>
</html>
