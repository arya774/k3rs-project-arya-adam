<!DOCTYPE html>
<html>
<head>
    <title>INSPEKSI K3 RSUD</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body { overflow-x: hidden; }

        canvas {
            width: 250px;
            height: 120px;
            border:1px solid #000;
            background: #fff;
        }

        .sidebar {
            position: fixed;
            width: 250px;
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

        .sidebar a.active {
            background: rgba(255,255,255,0.2);
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .step { display: none; }
        .step.active { display: block; }
    </style>
</head>

<body>

<div class="sidebar">
    <a href="#" onclick="showStep(1)" id="menu1" class="active">Master Data</a>
    <a href="#" onclick="showStep(2)" id="menu2">Form Inspeksi</a>
    <a href="{{ route('inspeksi.dashboard') }}">Dashboard</a>
</div>

<div class="content">
<h1>INSPEKSI K3 RSUD</h1>

<!-- STEP 1 -->
<div class="step active" id="step1">

<h5>Kategori</h5>
<form id="formKategori">
    <input type="text" name="nama_kategori" class="form-control mb-2" required>
    <button class="btn btn-primary w-100">Tambah</button>
</form>
<h5>Uraian</h5>
<form id="formUraian">
    <select name="kategori_id" id="kategori" class="form-select mb-2" required>
        <option value="">Pilih Kategori</option>
        @foreach($kategoris as $k)
            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
        @endforeach
    </select>

    <input type="text" id="inputUraian" class="form-control mb-2" placeholder="Masukkan Uraian" required>
    <button class="btn btn-primary w-100">Tambah</button>
</form>

<h5>Sub Uraian</h5>
<form id="formSub">

<select name="uraian_id" class="form-select mb-2" required>
@foreach($kategoris as $k)
    @foreach($k->uraian as $u)
        <option value="{{ $u->id }}">{{ $u->nama_uraian }}</option>
    @endforeach
@endforeach
</select>

<div id="sub-container">
<input type="text" name="nama_sub_uraian[]" class="form-control mb-2 sub-input" placeholder="Ketik lalu Enter">
</div>

<button class="btn btn-primary w-100">Tambah</button>
</form>

</div>

<!-- STEP 2 -->
<div class="step" id="step2">

<form id="formInspeksi" method="POST" action="{{ route('inspeksi.store') }}">
@csrf

<div class="row">
<div class="col-md-6 mb-2">
<label>Tanggal</label>
<input type="date" id="tanggal" name="tanggal" class="form-control">
</div>

<div class="col-md-6 mb-2">
<label>Ruangan</label>
<input type="text" name="ruangan" class="form-control">
</div>
</div>

<div class="card mt-3 mb-3">
<div class="card-header bg-success text-white">Data Petugas</div>
<div class="card-body">

<input type="text" name="nama_petugas_k3rs" class="form-control mb-2" placeholder="Petugas K3RS">

<canvas id="signature-pad-k3rs"></canvas>
<input type="hidden" id="paraf_k3rs" name="paraf_petugas_k3rs">

<button type="button" class="btn btn-danger btn-sm mt-2" onclick="clearK3rs()">Hapus TTD</button>

<hr>

<input type="text" name="nama_petugas_ruangan" class="form-control mb-2" placeholder="Petugas Ruangan">

<canvas id="signature-pad-ruangan"></canvas>
<input type="hidden" id="paraf_ruangan" name="paraf_petugas_ruangan">

<button type="button" class="btn btn-danger btn-sm mt-2" onclick="clearRuangan()">Hapus TTD</button>

</div>
</div>

<div class="mb-3">
<select id="filterKategori" class="form-control">
<option value="">Pilih Kategori</option>
@foreach($kategoris as $k)
<option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
@endforeach
</select>
</div>

@foreach($kategoris as $k)
<div class="kategori-box" id="kategori-{{ $k->id }}" style="display:none;">
<div class="card mb-3">
<div class="card-header bg-primary text-white">{{ $k->nama_kategori }}</div>
<div class="card-body">

@foreach($k->uraian as $u)
<b>{{ $u->nama_uraian }}</b>

@foreach($u->subUraian as $s)
<div class="mb-2">
{{ $s->nama_sub_uraian }}
<label><input type="radio" name="nilai[{{ $s->id }}]" value="ya"> Ya</label>
<label><input type="radio" name="nilai[{{ $s->id }}]" value="tidak"> Tidak</label>
</div>

<textarea name="catatan[{{ $s->id }}]" class="form-control mb-2"></textarea>
@endforeach

<hr>
@endforeach

</div>
</div>
</div>
@endforeach

<button id="btnSimpan" class="btn btn-success w-100">Simpan Inspeksi</button>

<div id="loading" class="text-center mt-2 d-none">
<div class="spinner-border text-primary"></div>
<p>Menyimpan...</p>
</div>

</form>
</div>

</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function(){

window.showStep = function(step){
    $('.step').removeClass('active');
    $('#step'+step).addClass('active');
}

// tanggal auto
$('#tanggal').val(new Date().toISOString().split('T')[0]);

// filter
$('#filterKategori').change(function(){
    $('.kategori-box').hide();
    $('#kategori-'+$(this).val()).show();
});

// signature
let padK3rs = new SignaturePad(document.getElementById('signature-pad-k3rs'));
let padRuangan = new SignaturePad(document.getElementById('signature-pad-ruangan'));

window.clearK3rs = ()=>padK3rs.clear();
window.clearRuangan = ()=>padRuangan.clear();

// VALIDASI + LOADING
$('#formInspeksi').submit(function(e){

    let valid = true;

    $('.kategori-box:visible input[type=radio]').each(function(){
        let name = $(this).attr('name');
        if(!$('input[name="'+name+'"]:checked').length){
            valid = false;
        }
    });

    if(!valid){
        Swal.fire('Error','Isi semua pertanyaan!','error');
        e.preventDefault();
        return;
    }

    $('#btnSimpan').prop('disabled', true).text('Menyimpan...');
    $('#loading').removeClass('d-none');

    $('#paraf_k3rs').val(padK3rs.toDataURL());
    $('#paraf_ruangan').val(padRuangan.toDataURL());
});

// SUB INPUT
$(document).on('keydown','.sub-input',function(e){
    if(e.key === 'Enter'){
        e.preventDefault();
        $('#sub-container').append('<input type="text" name="nama_sub_uraian[]" class="form-control mb-2 sub-input">');
    }
});

// CSRF
$.ajaxSetup({
headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}
});

// KATEGORI
$('#formKategori').submit(function(e){
    e.preventDefault();
    $.post('/inspeksi/master/kategori', $(this).serialize(), function(){
        Swal.fire('Sukses','Kategori ditambahkan','success')
        .then(()=>location.reload());
    });
});

// URAIAN
$('#formUraian').submit(function(e){
    e.preventDefault();
    $.post('/inspeksi/master/uraian',{
        kategori_id: $('#kategori').val(),
        nama_uraian: $('#inputUraian').val()
    },function(){
        Swal.fire('Sukses','Uraian ditambahkan','success')
        .then(()=>location.reload());
    });
});

// SUB
$('#formSub').submit(function(e){
    e.preventDefault();
    $.post('/inspeksi/master/suburaian', $(this).serialize(), function(){
        Swal.fire('Sukses','Sub Uraian ditambahkan','success')
        .then(()=>location.reload());
    });
});

});
</script>

</body>
</html>
