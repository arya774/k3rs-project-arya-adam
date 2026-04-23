<!DOCTYPE html>
<html>
<head>
    <title>Sub Uraian</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body{
            background: #f4f8ff;
        }

        .card-glass{
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border: none;
        }

        .header-blue{
            background: linear-gradient(135deg,#0d6efd,#0a58ca);
            color: white;
            padding: 15px;
            border-radius: 15px 15px 0 0;
        }

        .btn-primary{
            background: #0d6efd;
            border: none;
        }

        .list-item{
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .list-item:hover{
            background: #f0f6ff;
        }
    </style>
</head>

<body class="p-4">

    <a href="/inspeksi/wizard" class="btn btn-secondary mb-3">
    ← Kembali
</a>

<div class="container">

    <div class="card card-glass">

        <div class="header-blue">
            <h4 class="mb-0">Sub Uraian Management</h4>
        </div>

        <div class="p-4">

            <!-- SELECT URAIAN -->
            <label class="form-label">Pilih Uraian</label>
            <select id="uraian" class="form-select mb-3">
                <option value="">Pilih Uraian</option>
            </select>

            <!-- INPUT -->
            <label class="form-label">Nama Sub Uraian</label>
            <input type="text" id="nama_sub" class="form-control mb-3" placeholder="Contoh: APD lengkap">

            <button id="btnSub" class="btn btn-primary w-100 mb-3">
                + Tambah Sub Uraian
            </button>

            <hr>

            <h6 class="mb-3">List Sub Uraian</h6>

            <div id="listSub"></div>

        </div>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});

// LOAD URAIAN
function loadUraian(){
    $.get('/get-uraian-all', function(data){

        let opt = '<option value="">Pilih Uraian</option>';

        data.forEach(u=>{
            opt += `<option value="${u.id}">${u.nama_uraian}</option>`;
        });

        $('#uraian').html(opt);
    });
}

// LOAD SUB URAIAN
function loadSub(){

    $.get('/get-sub-all', function(data){

        let html = '';

        data.forEach(s=>{
            html += `
            <div class="list-item">
                <div>${s.nama_sub_uraian}</div>
                <button class="btn btn-sm btn-danger btn-hapus" data-id="${s.id}">
                    Hapus
                </button>
            </div>`;
        });

        $('#listSub').html(html);
    });
}

// TAMBAH
$('#btnSub').click(function(){

    $.post('/inspeksi/master/suburaian',{
        uraian_id: $('#uraian').val(),
        nama_sub_uraian: $('#nama_sub').val()
    },function(){

        $('#nama_sub').val('');
        loadSub();
    });
});

// HAPUS
$(document).on('click','.btn-hapus',function(){

    let id = $(this).data('id');

    $.ajax({
        url: '/inspeksi/master/suburaian/' + id,
        type: 'DELETE',
        success: function(){
            loadSub();
        }
    });

});

// INIT
loadUraian();
loadSub();

</script>

</body>
</html>
