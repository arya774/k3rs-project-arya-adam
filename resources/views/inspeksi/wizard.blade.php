<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body { overflow-x: hidden; }

        canvas {
            display: block;
            width: 100%;
            height: 200px;
            touch-action: none;
         }

        .sidebar {
            position: fixed;
            width: 230px;
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

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,0.2);
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .step { display: none; }
        .step.active { display: block; }

        .card { border-radius: 10px; }
    </style>
</head>

<body>

<div class="sidebar">
    <h4>TUTI FATIMAH</h4>

    <a href="#" onclick="showStep(1)" id="menu1" class="active">Master Data</a>
    <a href="#" onclick="showStep(2)" id="menu2">Form Inspeksi</a>
    <a href="{{ route('inspeksi.dashboard') }}">Dashboard</a>
</div>

<div class="content">

    <div class="d-flex align-items-center mb-4">
        <img src="{{ asset('images/logo_rsud.png') }}" style="height:60px; margin-right:10px;">
        <h2 class="m-0">INSPEKSI K3 RSUD KOTA BOGOR</h2>
    </div>

    <!-- ================= STEP 1 ================= -->
    <div class="step active" id="step1">
        <h4>Master Data Inspeksi</h4>

        <h5>Kategori</h5>
        <form id="formKategori" class="mb-3">
            <input type="text" name="nama_kategori" class="form-control mb-2" required>
            <button class="btn btn-primary w-100">Tambah</button>
        </form>

        <h5>Uraian</h5>
        <form id="formUraian" class="mb-3">
            <select name="kategori_id" class="form-select mb-2" required>
                <option value="">Pilih Kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
            <input type="text" name="nama_uraian" class="form-control mb-2" required>
            <button class="btn btn-primary w-100">Tambah</button>
        </form>

        <h5>Sub-Uraian</h5>
        <form id="formSub" class="mb-3">
            <select name="uraian_id" class="form-select mb-2" required>
                @foreach($kategoris as $k)
                    @foreach($k->uraian as $u)
                        <option value="{{ $u->id }}">
                            {{ $k->nama_kategori }} → {{ $u->nama_uraian }}
                        </option>
                    @endforeach
                @endforeach
            </select>
            <input type="text" name="nama_sub_uraian" class="form-control mb-2" required>
            <button class="btn btn-primary w-100">Tambah</button>
        </form>
    </div>

   <!-- ================= STEP 2 ================= -->
<div class="step" id="step2">
    <h4>Formulir Inspeksi RSUD</h4>
    <form id="formInspeksi" action="{{route('inspeksi.store')}}" method="POST">
         @csrf

    <!-- 🔥 FORM WAJIB ADA -->
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

        <!-- ================= PETUGAS ================= -->
        <div class="card mt-3 mb-3">
            <div class="card-header bg-success text-white">Data Petugas</div>
            <div class="card-body">

                <!-- PETUGAS K3RS -->
                <div class="mb-4">
                    <label>Nama Petugas K3RS</label>
                    <input type="text" name="nama_petugas_k3rs" class="form-control mb-2" required>

                    <label>Paraf</label>
                    <canvas id="signature-pad-k3rs" style="border:1px solid #000; width:200px; height:150px;"></canvas>
                    <input type="hidden" name="paraf_petugas_k3rs" id="paraf_k3rs">

                    <button type="button" class="btn btn-sm btn-danger mt-2" onclick="clearK3rs()">Hapus</button>
                </div>

                <!-- PETUGAS RUANGAN -->
                <div class="mb-4">
                    <label>Nama Petugas Ruangan</label>
                    <input type="text" name="nama_petugas_ruangan" class="form-control mb-2" required>

                    <label>Paraf</label>
                    <canvas id="signature-pad-ruangan" style="border:1px solid #000; width:200px; height:150px;"></canvas>
                    <input type="hidden" name="paraf_petugas_ruangan" id="paraf_ruangan">

                    <button type="button" class="btn btn-sm btn-danger mt-2" onclick="clearRuangan()">Hapus</button>
                </div>

            </div>
        </div>

        <!-- ================= FILTER ================= -->
        <div class="mb-3">
            <label>Pilih Kategori</label>
            <select id="filterKategori" class="form-select">
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <!-- ================= DATA ================= -->
        @foreach($kategoris as $k)
        <div class="kategori-box" id="kategori-{{ $k->id }}" style="display:none;">
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-primary text-white">
                    {{ $k->nama_kategori }}
                </div>

                <div class="card-body">
                    @foreach($k->uraian as $u)
                        <h5 class="text-primary">{{ $u->nama_uraian }}</h5>

                        @foreach($u->suburaian as $s)
                        <div class="mb-3 ms-3 p-3 border rounded bg-light">

                            <strong>{{ $s->nama_sub_uraian }}</strong>

                            <div class="mt-2">
                                <label class="me-3">
                                    <input type="radio" name="nilai[{{ $s->id }}]" value="ya">
                                    <span class="text-success fw-bold">Ya</span>
                                </label>

                                <label>
                                    <input type="radio" name="nilai[{{ $s->id }}]" value="tidak">
                                    <span class="text-danger fw-bold">Tidak</span>
                                </label>
                            </div>

                            <input type="text"
                                   name="catatan[{{ $s->id }}]"
                                   class="form-control mt-2"
                                   placeholder="Catatan (opsional)">
                        </div>
                        @endforeach

                    @endforeach
                </div>
            </div>
        </div>
        @endforeach

            <button class="btn btn-success w-100">
                💾 Simpan
            </button>
        </form>
    </div>


    </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
// STEP
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

    // =====================
    // PARAF
    // =====================
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
    $.post('/inspeksi/master/uraian', $(this).serialize(), () => location.reload());
});

$('#formSub').submit(function(e){
    e.preventDefault();
    $.post('/inspeksi/master/suburaian', $(this).serialize(), () => location.reload());
});
</script>
<script>
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
</script>


</body>
</html>
