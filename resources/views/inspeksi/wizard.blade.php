<!DOCTYPE html>
<html>
<head>
    <title>Wizard Inspeksi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{ background:#f4f8ff; }

        .sidebar{
            position:fixed;
            width:250px;
            height:100vh;
            background:#0d6efd;
            color:white;
            padding:20px;
        }

        .sidebar a{
            color:white;
            text-decoration:none;
            padding:8px;
            display:block;
            border-radius:6px;
        }

        .sidebar a.active,
        .sidebar a:hover{
            background:rgba(255,255,255,0.2);
        }

        .content{
            margin-left:260px;
            padding:20px;
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
            margin:5px;
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h5>INSPEKSI K3</h5>
    <hr>

    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('inspeksi.index') }}" class="active">Form Inspeksi</a>
    <a href="{{ route('laporan.index') }}">Laporan</a>
</div>

<div class="content">

<h3>Form Inspeksi</h3>

<form id="formInspeksi" method="POST" action="{{ route('inspeksi.store') }}" enctype="multipart/form-data">
@csrf

<!-- STEP 1 -->
<div id="step1" class="step active">

    <div class="row">
        <div class="col-md-6 mb-2">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="col-md-6 mb-2">
            <label>Ruangan</label>
            <input type="text" name="ruangan" class="form-control" required>
        </div>
    </div>

    <!-- TTD K3RS -->
    <label class="mt-2">TTD Petugas K3RS</label>
    <canvas id="padK3rs" width="300" height="120"></canvas>
    <input type="hidden" name="paraf_petugas_k3rs" id="ttd_k3rs">
    <button type="button" class="btn btn-danger btn-sm mt-1" id="clearK3rs">Hapus</button>

    <input type="text" name="nama_petugas_k3rs" class="form-control mt-2" placeholder="Nama Petugas K3RS" required>

    <!-- TTD RUANGAN -->
    <label class="mt-3">TTD Petugas Ruangan</label>
    <canvas id="padRuangan" width="300" height="120"></canvas>
    <input type="hidden" name="paraf_petugas_ruangan" id="ttd_ruangan">
    <button type="button" class="btn btn-danger btn-sm mt-1" id="clearRuangan">Hapus</button>

    <input type="text" name="nama_petugas_ruangan" class="form-control mt-2" placeholder="Nama Petugas Ruangan" required>

    <div class="mt-3 text-end">
        <button type="button" class="btn btn-primary" onclick="nextStep()">Lanjut →</button>
    </div>

</div>

<!-- STEP 2 -->
<div class="step card p-3" id="step2">

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

                    <span>{{ $s->nama_sub_uraian }}</span>

                    <div class="mt-2">
                        <label><input type="radio" name="nilai[{{ $s->id }}]" value="ya"> Ya</label>
                        <label class="ms-2"><input type="radio" name="nilai[{{ $s->id }}]" value="tidak"> Tidak</label>
                    </div>

                    <textarea name="catatan_multi[{{ $s->id }}]" class="form-control mt-2"></textarea>

                </div>

                @endforeach

                <hr>

            @endforeach

        </div>
    </div>
    @endforeach

    <!-- FOTO -->
    <div class="card p-3 mt-3">
        <label><b>Upload Foto (Opsional)</b></label>
        <input type="file" name="foto[]" id="foto" class="form-control" multiple>
        <div id="preview" class="d-flex flex-wrap mt-2"></div>
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

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>

function nextStep(){
    $('#step1').removeClass('active');
    $('#step2').addClass('active');
}

$('#filterKategori').on('change', function(){
    $('.kategori-box').hide();
    if($(this).val()){
        $('#kategori-'+$(this).val()).show();
    }
});

let padK3rs = new SignaturePad(document.getElementById('padK3rs'));
let padRuangan = new SignaturePad(document.getElementById('padRuangan'));

$('#clearK3rs').click(()=>padK3rs.clear());
$('#clearRuangan').click(()=>padRuangan.clear());

$('#formInspeksi').on('submit', function(e){

    console.log("SUBMIT JALAN");

    if($('input[type=radio]:checked').length === 0){
        alert("Checklist belum diisi!");
        e.preventDefault();
        return;
    }

    if(padK3rs.isEmpty() || padRuangan.isEmpty()){
        alert("TTD wajib diisi!");
        e.preventDefault();
        return;
    }

    document.getElementById('ttd_k3rs').value = padK3rs.toDataURL();
    document.getElementById('ttd_ruangan').value = padRuangan.toDataURL();

});

$('#foto').on('change', function(){
    let preview = $('#preview');
    preview.html('');

    Array.from(this.files).forEach(file => {
        let reader = new FileReader();
        reader.onload = e => preview.append(`<img src="${e.target.result}">`);
        reader.readAsDataURL(file);
    });
});

</script>

</body>
</html>