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

    <input type="date" name="tanggal" class="form-control mb-2">
    <input type="text" name="ruangan" class="form-control mb-2" placeholder="Ruangan">

    <input type="text" name="nama_petugas_k3rs" class="form-control mb-2" placeholder="Petugas K3RS">

    <canvas id="pad1" width="300" height="120"></canvas>
    <input type="hidden" name="paraf_petugas_k3rs" id="ttd1">

    <button type="button" class="btn btn-danger btn-sm mt-1" onclick="pad1.clear()">Hapus TTD</button>

    <input type="text" name="nama_petugas_ruangan" class="form-control mt-3 mb-2" placeholder="Petugas Ruangan">

    <canvas id="pad2" width="300" height="120"></canvas>
    <input type="hidden" name="paraf_petugas_ruangan" id="ttd2">

    <button type="button" class="btn btn-danger btn-sm mt-1" onclick="pad2.clear()">Hapus TTD</button>

    <button type="button" class="btn btn-primary mt-3" onclick="nextStep()">Lanjut</button>

</div>

<!-- STEP 2 -->
<div id="step2" class="step">

    <select id="filterKategori" class="form-control mb-3">
        <option value="">Pilih Kategori</option>
        @foreach($kategoris as $k)
            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
        @endforeach
    </select>

    @foreach($kategoris as $k)
    <div class="kategori-box" id="kat-{{ $k->id }}" style="display:none">

        <h5>{{ $k->nama_kategori }}</h5>

        @foreach($k->uraian as $u)

            <b>{{ $u->nama_uraian }}</b>

            @foreach($u->subUraian as $s)

            <div class="border p-2 mb-2">

                {{ $s->nama_sub_uraian }}

                <div>
                    <label>
                      <input type="radio" name="nilai[{{ $s->id }}]" value="ya" required> Ya
                    </label>

                    <label>
                        <input type="radio" name="nilai[{{ $s->id }}]" value="tidak" required> Tidak
                    </label>
                </div>

                <textarea name="catatan_multi[{{ $s->id }}]" class="form-control mt-1" placeholder="Catatan"></textarea>

            </div>

            @endforeach

        @endforeach

    </div>
    @endforeach

    <button type="submit" class="btn btn-success w-100 mt-3">Simpan</button>

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

    if(this.value){
        document.getElementById('kat-'+this.value).style.display='block';
    }
});

let pad1 = new SignaturePad(document.getElementById('pad1'));
let pad2 = new SignaturePad(document.getElementById('pad2'));

document.getElementById('formInspeksi').addEventListener('submit', function(e){

    let semuaSub = document.querySelectorAll("input[type=radio]");
    let yangDipilih = document.querySelectorAll("input[type=radio]:checked");

    if(yangDipilih.length === 0){
        alert("Minimal isi 1 sub uraian!");
        e.preventDefault();
        return;
    }

});

</script>

</body>
</html>
