<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!DOCTYPE html>

<style>
    body { overflow-x: hidden; }

    canvas {
    display: block;
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
        <button type="submit" class="btn btn-primary w-100">Tambah</button>
    </form>

    <h5>Uraian</h5>
    <form id="formUraian">
        <select id="kategori" class="form-select mb-2">
            <option value="">Pilih Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
            @endforeach
        </select>

       <input type="text" id="inputUraian" class="form-control mb-2" placeholder="Masukkan Uraian">

        <button type="submit" class="btn btn-primary w-100">Tambah</button>
    </form>
    <select id="kategoriSub" class="form-select mb-2">
    <option value="">Pilih Kategori</option>
    @foreach($kategoris as $k)
        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
    @endforeach
</select>

    <h5>Sub Uraian</h5>
    <form id="formSub">
        <select name="uraian_id" class="form-select mb-2">
            @foreach($kategoris as $k)
                @foreach($k->uraian as $u)
                    <option value="{{ $u->id }}">{{ $u->nama_uraian }}</option>
                @endforeach
            @endforeach
        </select>
        <input type="text" name="nama_sub_uraian" class="form-control mb-2" required>
        <button type="submit" class="btn btn-primary w-100">Tambah</button>
    </form>
</div>


<!-- STEP 2 -->
<div class="step" id="step2">

    <form id="formInspeksi" method="POST" action="{{ route('inspeksi.store') }}">
        @csrf

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

        <div class="card mt-3 mb-3">
            <div class="card-header bg-success text-white">Data Petugas</div>
            <div class="card-body">

                <label>Nama Petugas K3RS</label>
                <input type="text" name="nama_petugas_k3rs" class="form-control mb-2" required>

                <canvas id="signature-pad-k3rs"></canvas>
                <input type="hidden" name="paraf_petugas_k3rs" id="paraf_k3rs">

                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="clearK3rs()">Hapus</button>

                <button type="button" onclick="tambahUraian()">Tambah</button>

                <hr>

                <label>Nama Petugas Ruangan</label>
                <input type="text" name="nama_petugas_ruangan" class="form-control mb-2" required>

                <canvas id="signature-pad-ruangan"></canvas>
                <input type="hidden" name="paraf_petugas_ruangan" id="paraf_ruangan">

                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="clearRuangan()">Hapus</button>
            </div>
        </div>

        @foreach($kategoris as $k)
        <div class="kategori-box" id="kategori-{{ $k->id }}" style="display:none;">
            @foreach($k->uraian as $u)
                <b>{{ $u->nama_uraian }}</b>

                @foreach($u->subUraian as $s)
                    <div class="mb-2">
                        {{ $s->nama_sub_uraian }}
                        <input type="radio" name="nilai[{{ $s->id }}]" value="ya"> Ya
                        <input type="radio" name="nilai[{{ $s->id }}]" value="tidak"> Tidak
                    </div>
                @endforeach
            @endforeach
        </div>
        @endforeach

        <button type="submit" class="btn btn-success mt-3 w-100">Simpan</button>

    </form>
</div>

</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
$(document).ready(function(){

    window.showStep = function(step){
        $('.step').removeClass('active');
        $('#step'+step).addClass('active');

        $('.sidebar a').removeClass('active');
        $('#menu'+step).addClass('active');
    }

    $('#tanggal').val(new Date().toISOString().split('T')[0]);

    $('#filterKategori').change(function(){
        $('.kategori-box').hide();
        $('#kategori-' + $(this).val()).show();
    });


    const signaturePadK3rs = new SignaturePad(document.getElementById('signature-pad-k3rs'));
    const signaturePadRuangan = new SignaturePad(document.getElementById('signature-pad-ruangan'));

    window.clearK3rs = () => signaturePadK3rs.clear();
    window.clearRuangan = () => signaturePadRuangan.clear();

    $('#formInspeksi').submit(function(){
        if (!signaturePadK3rs.isEmpty()) $('#paraf_k3rs').val(signaturePadK3rs.toDataURL());
        if (!signaturePadRuangan.isEmpty()) $('#paraf_ruangan').val(signaturePadRuangan.toDataURL());
    });

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#formKategori').submit(function(e){
        e.preventDefault();
        $.post('/inspeksi/master/kategori', $(this).serialize(), () => location.reload());
    });

    $('#formSub').submit(function(e){
        e.preventDefault();
        $.post('/inspeksi/master/suburaian', $(this).serialize(), () => location.reload());
    });

});
</script>

</body>

<script>
function showStep(step){
    $('.step').removeClass('active');
    $('#step'+step).addClass('active');

    $('.sidebar a').removeClass('active');
    $('#menu'+step).addClass('active');
}


// AUTO TANGGAL
$('#tanggal').val(new Date().toISOString().split('T')[0]);

// FILTER
$('#filterKategori').change(function(){
    let id = $(this).val();
    $('.kategori-box').hide();
    if(id !== '') $('#kategori-' + id).show();
});

// VALIDASI FIX
$('#formInspeksi').on('submit', function(e){

    let valid = true;

    // ✅ hanya cek yang terlihat saja
    $('.kategori-box:visible').find('input[type=radio]').each(function(){

        let name = $(this).attr('name');

        if($('input[name="'+name+'"]:checked').length === 0){
            valid = false;
        }

    });

    if(!valid){
        alert('Isi semua pertanyaan pada kategori yang dipilih!');
        e.preventDefault();
        return;
    }

    if (!signaturePadK3rs.isEmpty()) {
        $('#paraf_k3rs').val(signaturePadK3rs.toDataURL());
    }

    if (!signaturePadRuangan.isEmpty()) {
        $('#paraf_ruangan').val(signaturePadRuangan.toDataURL());
    }

});
    $('.kategori-box:visible input[type=radio]').each(function(){

        let name = $(this).attr('name');

        if($('input[name="'+name+'"]:checked').length === 0){
            valid = false;
        }

    });

    if(!valid){
        alert('Isi semua pertanyaan di kategori yang dipilih!');
        e.preventDefault();
    }


// AJAX MASTER
$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});

$('#formKategori').submit(function(e){
    e.preventDefault();
    $.post('/inspeksi/master/kategori', $(this).serialize(), () => location.reload());
});

$('#formUraian').submit(function(e){
    e.preventDefault();

    let kategori = $('#kategori').val();
    let uraian = $('#inputUraian').val();

    if(kategori === '' || uraian === ''){
        alert('Kategori dan Uraian harus diisi!');
        return;
    }

    $.ajax({
        url: '/inspeksi/master/uraian',
        type: 'POST',
        data: {
            kategori_id: kategori,
            nama_uraian: uraian,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(){
            alert('Data berhasil disimpan');

            // 🔥 LANGSUNG UPDATE DROPDOWN TANPA RELOAD
            $('#kategoriSub').trigger('change');

            // kosongkan input
            $('#inputUraian').val('');
        },
        error: function(err){
            console.log(err);
            alert('Gagal simpan');
        }
    });
});
    <!-- PILIH KATEGORI -->
    <select name="kategori_id" required>
        <option value="">Pilih Kategori</option>
        @foreach($kategoris as $k)
            <option value="{{ $k->id }}">{{ $k->nama }}</option>
        @endforeach
    </select>

    <!-- INPUT URAIAN -->
    <input type="text" name="uraian" placeholder="Masukkan Uraian" required>

    <!-- TOMBOL -->
    <button type="submit">Tambah</button>
</form>

    $.ajax({
        url: '/inspeksi/master/uraian',
        type: 'POST',
        data: {
            kategori_id: kategori,
            nama_uraian: uraian,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(){
            alert('Data berhasil disimpan');
            location.reload();
        },
        error: function(err){
            console.log(err);
            alert('Gagal simpan, cek console');
        }
    });
$('#formSub').submit(function(e){
    e.preventDefault();
    $.post('/inspeksi/master/suburaian', $(this).serialize(), () => location.reload());
});

// SIGNATURE K3RS
const canvasK3rs = document.getElementById('signature-pad-k3rs');
const signaturePadK3rs = new SignaturePad(canvasK3rs);

function clearK3rs() {
    signaturePadK3rs.clear();
}

// SIGNATURE RUANGAN
const canvasRuangan = document.getElementById('signature-pad-ruangan');
const signaturePadRuangan = new SignaturePad(canvasRuangan);

function clearRuangan() {
    signaturePadRuangan.clear();
}

// SAAT SUBMIT → UBAH JADI BASE64
$('#formInspeksi').submit(function(){

    if (!signaturePadK3rs.isEmpty()) {
        $('#paraf_k3rs').val(signaturePadK3rs.toDataURL());
    }

    if (!signaturePadRuangan.isEmpty()) {
        $('#paraf_ruangan').val(signaturePadRuangan.toDataURL());
    }

});

function resizeCanvas(canvas, signaturePad) {
    const ratio = Math.max(window.devicePixelRatio || 1, 1);

    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;

    canvas.getContext("2d").scale(ratio, ratio);

    signaturePad.clear();
}

<script>
$(document).ready(function(){

    window.showStep = function(step){
        $('.step').removeClass('active');
        $('#step'+step).addClass('active');

        $('.sidebar a').removeClass('active');
        $('#menu'+step).addClass('active');
    }

    $('#tanggal').val(new Date().toISOString().split('T')[0]);

    $('#filterKategori').change(function(){
        $('.kategori-box').hide();
        $('#kategori-' + $(this).val()).show();
    });

    const signaturePadK3rs = new SignaturePad(document.getElementById('signature-pad-k3rs'));
    const signaturePadRuangan = new SignaturePad(document.getElementById('signature-pad-ruangan'));

    window.clearK3rs = () => signaturePadK3rs.clear();
    window.clearRuangan = () => signaturePadRuangan.clear();

    $('#formInspeksi').on('submit', function(e){

        let valid = true;

        $('.kategori-box:visible input[type=radio]').each(function(){
            let name = $(this).attr('name');

            if($('input[name="'+name+'"]:checked').length === 0){
                valid = false;
            }
        });

        if(!valid){
            alert('Isi semua pertanyaan!');
            e.preventDefault();
            return;
        }

        if (!signaturePadK3rs.isEmpty()) {
            $('#paraf_k3rs').val(signaturePadK3rs.toDataURL());
        }

        if (!signaturePadRuangan.isEmpty()) {
            $('#paraf_ruangan').val(signaturePadRuangan.toDataURL());
        }
    });

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#formKategori').submit(function(e){
        e.preventDefault();
        $.post('/inspeksi/master/kategori', $(this).serialize(), () => location.reload());
    });

    $('#formSub').submit(function(e){
        e.preventDefault();
        $.post('/inspeksi/master/suburaian', $(this).serialize(), () => location.reload());
    });

});
</script>
