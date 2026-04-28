<!DOCTYPE html>
<html>
<head>
    <title>Form Inspeksi K3RS + Master Data</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{ background:#f4f8ff; }

        .card{
            border-radius:12px;
            box-shadow:0 5px 15px rgba(0,0,0,0.05);
        }

        .tag{
            display:inline-block;
            background:#0d6efd;
            color:white;
            padding:5px 10px;
            border-radius:6px;
            margin:3px;
            font-size:13px;
        }

        .tag button{
            border:none;
            background:none;
            color:white;
            margin-left:6px;
            cursor:pointer;
        }
    </style>
</head>

<body>
<div class="container mt-4">

    <h2>Master Data + Inspeksi K3RS</h2>

    {{-- ================= KATEGORI ================= --}}
    <form action="{{ route('kategori.store') }}" method="POST" class="mb-3">@csrf
        <div class="input-group">
            <input type="text" name="nama_kategori" class="form-control" placeholder="Kategori">
            <button class="btn btn-primary">Tambah</button>
        </div>
    </form>

    {{-- ================= URAIAN ================= --}}
    <form action="{{ route('uraian.store') }}" method="POST" class="mb-3">@csrf
        <div class="input-group">

            <select name="kategori_id" class="form-select" required>
                <option value="">Pilih Kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>

            <input type="text" name="nama_uraian" class="form-control" placeholder="Uraian">

            <button class="btn btn-primary">Tambah</button>
        </div>
    </form>

    {{-- ================= SUB URAIAN MULTI INPUT ================= --}}
    <form action="{{ route('suburaian.store') }}" method="POST" id="formSub">
    @csrf

        <select name="uraian_id" class="form-select mb-2" required>
            <option value="">Pilih Uraian</option>
            @foreach($kategoris as $k)
                @foreach($k->uraian as $u)
                
                        {{ $k->nama_kategori }} → {{ $u->nama_uraian }}
                    </option>
                @endforeach
            @endforeach
        </select>

        <input type="text" id="subInput" class="form-control mb-2"
               placeholder="Ketik sub uraian lalu tekan ENTER">

        <div id="tagBox" class="mb-2"></div>

        <button class="btn btn-primary">Simpan Sub Uraian</button>

    </form>

    <hr>

    {{-- ================= INSPEKSI ================= --}}
    <form action="{{ route('inspeksi.store') }}" method="POST">
    @csrf

        <div class="card p-3 mb-3">
            <div class="row">
                <div class="col-md-6">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Ruangan</label>
                    <input type="text" name="ruangan" class="form-control">
                </div>
            </div>
        </div>

        <div class="card p-3 mb-3">
            <input type="text" name="nama_petugas_k3rs" class="form-control mb-2" placeholder="Petugas K3RS">
            <input type="text" name="nama_petugas_ruangan" class="form-control" placeholder="Petugas Ruangan">
        </div>

        {{-- CHECKLIST --}}
        @foreach($kategoris as $kategori)
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                {{ $kategori->nama_kategori }}
            </div>

            <div class="card-body">

                @foreach($kategori->uraian as $uraian)

                    <h6>{{ $uraian->nama_uraian }}</h6>

                    @foreach($uraian->suburaian as $sub)

                        <div class="border p-2 mb-2">

                            <b>{{ $sub->nama_sub_uraian }}</b>

                            <div>
                                <label><input type="radio" name="nilai[{{ $sub->id }}]" value="ya"> Ya</label>
                                <label><input type="radio" name="nilai[{{ $sub->id }}]" value="tidak"> Tidak</label>
                            </div>

                            <textarea name="catatan[{{ $sub->id }}]" class="form-control mt-2"></textarea>

                        </div>

                    @endforeach

                @endforeach

            </div>
        </div>
        @endforeach

        <button class="btn btn-success w-100">Simpan Inspeksi</button>

    </form>

</div>

{{-- ================= SCRIPT SUB URAIAN MULTI ================= --}}
<script>
let subList = [];

// ENTER = tambah item
document.getElementById('subInput').addEventListener('keydown', function(e){
    if(e.key === 'Enter'){
        e.preventDefault();

        let val = this.value.trim();
        if(val === '') return;

        subList.push(val);
        this.value = '';

        render();
    }
});

function render(){
    let box = document.getElementById('tagBox');
    box.innerHTML = '';

    subList.forEach((item, i) => {
        box.innerHTML += `
            <span class="tag">
                ${item}
                <button type="button" onclick="hapus(${i})">×</button>
            </span>
        `;
    });
}

function hapus(i){
    subList.splice(i,1);
    render();
}

// kirim ke Laravel sebelum submit
document.getElementById('formSub').addEventListener('submit', function(e){

    if(subList.length === 0){
        alert('Isi minimal 1 sub uraian');
        e.preventDefault();
        return;
    }

    subList.forEach(val => {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'nama_sub_uraian[]';
        input.value = val;
        this.appendChild(input);
    });

});
</script>

</body>
</html>
