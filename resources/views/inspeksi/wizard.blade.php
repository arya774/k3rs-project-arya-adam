<!DOCTYPE html>
<html>
<head>
    <title>Wizard Inspeksi K3 RSUD</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body{
            background:#f4f8ff;
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg,#0d6efd,#0a58ca);
            color: white;
            padding: 20px;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 6px;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.15);
        }

        .content {
            margin-left: 250px;
            padding: 25px;
        }

        .card-glass{
            background: white;
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .step{ display:none; }
        .step.active{ display:block; }

        canvas{
            border:1px solid #ddd;
            border-radius: 10px;
            background:white;
            max-width: 100%;
        }

        #preview img{
            width:110px;
            border-radius:10px;
            margin-right:10px;
            margin-bottom:10px;
            box-shadow:0 3px 10px rgba(0,0,0,0.2);
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h5>INSPEKSI K3</h5>
    <hr>

    <a href="{{ route('dashboard') }}">📊 Dashboard</a>
    <a href="{{ route('inspeksi.wizard') }}">📝 Form Inspeksi</a>
    <a href="{{ route('laporan.index') }}">📄 Laporan</a>
</div>

<div class="content">

    <h3 class="mb-4">Form Inspeksi K3 RSUD</h3>

    <form id="formInspeksi" method="POST" action="{{ route('inspeksi.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- STEP 1 -->
        <div class="step active card card-glass p-3" id="step1">

            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                </div>

                <div class="col-md-6 mb-2">
                    <label>Ruangan</label>
                    <input type="text" name="ruangan" class="form-control" required>
                </div>
            </div>

            <hr>

            <h5>Petugas</h5>

            <input type="text" name="nama_petugas_k3rs" class="form-control mb-2" placeholder="Petugas K3RS" required>

            <canvas id="signature-pad-k3rs" width="300" height="120"></canvas>
            <input type="hidden" name="paraf_petugas_k3rs" id="paraf_k3rs">

            <button type="button" class="btn btn-danger btn-sm mt-2 mb-3" id="clearK3rs">Hapus TTD</button>

            <input type="text" name="nama_petugas_ruangan" class="form-control mb-2" placeholder="Petugas Ruangan" required>

            <canvas id="signature-pad-ruangan" width="300" height="120"></canvas>
            <input type="hidden" name="paraf_petugas_ruangan" id="paraf_ruangan">

            <button type="button" class="btn btn-danger btn-sm mt-2 mb-3" id="clearRuangan">Hapus TTD</button>

            <div class="mt-3 text-end">
                <button type="button" class="btn btn-primary" onclick="showStep(2)">Lanjut →</button>
            </div>

        </div>

        <!-- STEP 2 -->
        <div class="step card card-glass p-3" id="step2">

            <h5 class="mb-3">Checklist Inspeksi</h5>

            <select id="filterKategori" class="form-control mb-3">
                <option value="">Pilih Kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>

            @foreach($kategoris as $k)
            <div class="kategori-box" id="kategori-{{ $k->id }}" style="display:none;">
                <div class="card mb-3 p-3">

                    <b class="text-primary">{{ $k->nama_kategori }}</b>
                    <hr>

                    @foreach($k->uraian as $u)

                        <b>{{ $u->nama_uraian }}</b>

                        @foreach($u->subUraian as $s)

                        <div class="border rounded p-2 mb-2">

                            <div class="d-flex justify-content-between">
                                <span>{{ $s->nama_sub_uraian }}</span>
                            </div>

                            <div class="mt-2">
                                <label><input type="radio" name="nilai[{{ $s->id }}]" value="ya"> Ya</label>
                                <label><input type="radio" name="nilai[{{ $s->id }}]" value="tidak"> Tidak</label>
                            </div>

                            <textarea name="catatan_multi[{{ $s->id }}]" class="form-control mt-2" placeholder="Catatan (opsional)"></textarea>

                        </div>

                        @endforeach

                        <hr>

                    @endforeach

                </div>
            </div>
            @endforeach

            <!-- FOTO OPTIONAL -->
            <div class="card p-3 mt-3">
                <label><b>Upload Foto (Opsional)</b></label>

                <input type="file" name="foto[]" id="foto" class="form-control" multiple accept="image/*">

                <small class="text-muted">Tidak wajib diisi</small>

                <div id="preview" class="mt-3 d-flex flex-wrap"></div>
            </div>

            <button type="submit" id="btnSimpan" class="btn btn-success w-100 mt-3">
                SIMPAN INSPEKSI
            </button>

            <div id="loading" class="text-center mt-2 d-none">
                <div class="spinner-border text-primary"></div>
                <p>Menyimpan...</p>
            </div>

        </div>

    </form>

</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>

function showStep(step){
    $('.step').removeClass('active');
    $('#step'+step).addClass('active');
}

$('#tanggal').val(new Date().toISOString().split('T')[0]);

$('#filterKategori').on('change', function(){
    $('.kategori-box').hide();
    if($(this).val()){
        $('#kategori-'+$(this).val()).show();
    }
});

let padK3rs = new SignaturePad(document.getElementById('signature-pad-k3rs'));
let padRuangan = new SignaturePad(document.getElementById('signature-pad-ruangan'));

$('#clearK3rs').click(()=>padK3rs.clear());
$('#clearRuangan').click(()=>padRuangan.clear());

$('#formInspeksi').on('submit', function(e){

    if(padK3rs.isEmpty() || padRuangan.isEmpty()){
        alert("Tanda tangan wajib diisi!");
        e.preventDefault();
        return;
    }

    $('#paraf_k3rs').val(padK3rs.toDataURL());
    $('#paraf_ruangan').val(padRuangan.toDataURL());

    $('#btnSimpan').prop('disabled',true).text('Menyimpan...');
    $('#loading').removeClass('d-none');
});

// preview foto
$('#foto').on('change', function(){

    let preview = $('#preview');
    preview.html('');

    Array.from(this.files).forEach(file => {

        let reader = new FileReader();

        reader.onload = function(e){
            preview.append(`<img src="${e.target.result}">`);
        }

        reader.readAsDataURL(file);
    });
});

</script>

</body>
</html>