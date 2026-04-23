<!DOCTYPE html>
<html>
<head>
    <title>Uraian</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            background: linear-gradient(120deg, #f8fbff, #eef5ff);
        }

        .card-glass {
            background: white;
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .top-bar {
            background: linear-gradient(90deg, #0d6efd, #1e88e5);
            color: white;
            padding: 18px;
            border-radius: 0 0 20px 20px;
        }

        .btn-primary {
            background: linear-gradient(90deg, #0d6efd, #1e88e5);
            border: none;
            border-radius: 10px;
        }

        .form-control, .form-select {
            border-radius: 10px;
        }

        table thead {
            background: #0d6efd;
            color: white;
        }

        table tbody tr:hover {
            background: #f1f7ff;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<div class="top-bar mb-4">
    <div class="container">
        <h4 class="mb-0">📋 MASTER URAIAN</h4>
    </div>
</div>

<div class="container mb-3">
    <a href="/inspeksi/wizard" class="btn btn-secondary">
        ← Kembali
    </a>
</div>

<div class="container">
    <div class="row">

        <!-- FORM -->
        <div class="col-md-4">
            <div class="card card-glass p-4">

                <h5 class="text-primary mb-3">Tambah Uraian</h5>

                <select id="kategori" class="form-select mb-2">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>

                <input type="text"
                       id="nama_uraian"
                       class="form-control mb-3"
                       placeholder="Nama uraian...">

                <button id="btnUraian" class="btn btn-primary w-100">
                    + Tambah Uraian
                </button>

            </div>
        </div>

        <!-- LIST -->
        <div class="col-md-8">
            <div class="card card-glass p-4">

                <h5 class="text-primary mb-3">Daftar Uraian</h5>

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Uraian</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="tableUraian"></tbody>

                </table>

            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>

// CSRF
$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});

// ======================
// TAMBAH URAIAN (FIXED)
// ======================
$('#btnUraian').click(function(){

    let kategori = $('#kategori').val();
    let nama = $('#nama_uraian').val();

    // VALIDASI
    if(kategori === ''){
        alert('❗ Pilih kategori dulu');
        return;
    }

    if(nama === ''){
        alert('❗ Nama uraian tidak boleh kosong');
        return;
    }

    $.post('/inspeksi/master/uraian', {
        kategori_id: kategori,
        nama_uraian: nama
    })
    .done(function(){
        $('#nama_uraian').val('');
        loadUraian();
        alert('✅ Berhasil ditambahkan');
    })
    .fail(function(xhr){
        console.log(xhr.responseText);
        alert('❌ Gagal tambah data (cek controller)');
    });

});


// ======================
// LOAD URAIAN (FIXED)
// ======================
function loadUraian(){

    $.get('/inspeksi/get-uraian-all', function(data){

        let html = '';

        data.forEach(u => {
            html += `
                <tr id="row-${u.id}">
                    <td>${u.kategori.nama_kategori}</td>
                    <td class="fw-semibold">${u.nama_uraian}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${u.id}">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;
        });

        $('#tableUraian').html(html);
    });

}


// ======================
// DELETE URAIAN (FIXED)
// ======================
$(document).on('click','.btn-delete',function(){

    let id = $(this).data('id');

    if(!confirm('Hapus uraian ini?')) return;

    $.ajax({
        url: '/inspeksi/uraian-delete/' + id,
        type: 'DELETE',
        success: function(res){
            if(res.success){
                loadUraian();
            } else {
                alert('Gagal hapus dari server');
            }
        },
        error: function(xhr){
            console.log(xhr.responseText); // 🔥 buat lihat error asli
            alert('ERROR SERVER');
        }
    });

});


// LOAD AWAL
loadUraian();

</script>

</body>
</html>
