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
            background:white;
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h5>INSPEKSI</h5>
</div>

<div class="content">

<h3>Form Inspeksi</h3>

<form id="formInspeksi" method="POST" action="{{ route('inspeksi.store') }}">
@csrf

<!-- STEP 1 -->
<div id="step1" class="step active">

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

    <canvas id="pad1" width="300" height="120"></canvas>
    <input type="hidden" name="paraf_petugas_k3rs" id="ttd1">

    <button type="button" class="btn btn-danger btn-sm mt-1" onclick="pad1.clear()">Hapus TTD</button>

            <input type="text" name="nama_petugas_k3rs" class="form-control mb-2" placeholder="Petugas K3RS" required>

    <canvas id="pad2" width="300" height="120"></canvas>
    <input type="hidden" name="paraf_petugas_ruangan" id="ttd2">

    <button type="button" class="btn btn-danger btn-sm mt-1" onclick="pad2.clear()">Hapus TTD</button>

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

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>

function nextStep(){
    document.getElementById('step1').classList.remove('active');
    document.getElementById('step2').classList.add('active');
}

document.getElementById('filterKategori').addEventListener('change', function(){
    document.getElementById('filterKategori').addEventListener(...).SignaturePad

$('#filterKategori').on('change', function(){
    $('.kategori-box').hide();
    if($(this).val()){
        $('#kategori-'+$(this).val()).show();
    }
});

let pad1 = new SignaturePad(document.getElementById('pad1'));
let pad2 = new SignaturePad(document.getElementById('pad2'));

$('#clearK3rs').click(()=>padK3rs.clear());
$('#clearRuangan').click(()=>padRuangan.clear());

    let semuaSub = document.querySelectorAll("input[type=radio]");
    let yangDipilih = document.querySelectorAll("input[type=radio]:checked");

    if(yangDipilih.length === 0){
        alert("Minimal isi 1 sub uraian!");
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
