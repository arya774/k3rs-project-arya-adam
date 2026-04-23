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

        .list-item{
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .list-item:hover{
            background: #f0f6ff;
        }

        .tag-box{
            background: #e7f1ff;
            padding: 6px 10px;
            border-radius: 8px;
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
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

            <!-- SELECT -->
            <label class="form-label">Pilih Uraian</label>
            <select id="uraian" class="form-select mb-3"></select>

            <!-- INPUT -->
            <label class="form-label">Nama Sub Uraian</label>
            <input type="text" id="nama_sub" class="form-control mb-2" placeholder="Ketik lalu tekan ENTER">

            <!-- PREVIEW -->
            <div id="previewList" class="mb-3"></div>

            <button id="btnSub" class="btn btn-primary w-100 mb-3">
                + Simpan Semua
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

let subList = [];

// ======================
// ENTER = TAMBAH LIST 🔥
// ======================
$('#nama_sub').keypress(function(e){

    if(e.which == 13){
        e.preventDefault();

        let val = $(this).val().trim();

        if(val === '') return;

        subList.push(val);
        renderPreview();

        $(this).val('');
    }

});

// ======================
// PREVIEW LIST
// ======================
function renderPreview(){

    let html = '';

    subList.forEach((item,index)=>{
        html += `
        <div class="tag-box">
            <span>${item}</span>
            <button class="btn btn-sm btn-danger" onclick="removeItem(${index})">x</button>
        </div>`;
    });

    $('#previewList').html(html);
}

// ======================
// HAPUS ITEM SEBELUM SIMPAN
// ======================
function removeItem(index){
    subList.splice(index,1);
    renderPreview();
}

// ======================
// LOAD URAIAN
// ======================
function loadUraian(){

    $.get('/inspeksi/get-uraian-all', function(data){

        let opt = '<option value="">Pilih Uraian</option>';

        data.forEach(u=>{
            opt += `<option value="${u.id}">${u.nama_uraian}</option>`;
        });

        $('#uraian').html(opt);
    });

}

// ======================
// LOAD DATA SUB
// ======================
function loadSub(){

    $.get('/inspeksi/get-sub-all', function(data){

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

// ======================
// SIMPAN 🔥 MULTI DATA
// ======================
$('#btnSub').click(function(){

    let uraian = $('#uraian').val();

    if(!uraian){
        alert('Pilih uraian dulu!');
        return;
    }

    if(subList.length === 0){
        alert('Isi minimal 1 data!');
        return;
    }

    $.post('/inspeksi/master/suburaian',{
        uraian_id: uraian,
        nama_sub_uraian: subList
    },function(res){

        subList = [];
        renderPreview();
        loadSub();

    }).fail(function(xhr){
        console.log(xhr.responseText);
        alert('Gagal simpan');
    });

});

// ======================
// DELETE
// ======================
$(document).on('click','.btn-hapus',function(){

    let id = $(this).data('id');

    if(!confirm('Yakin hapus?')) return;

    $.ajax({
        url: '/suburaian/' + id,
        type: 'DELETE',
        success: function(){
            loadSub();
        },
        error: function(xhr){
            console.log(xhr.responseText);
            alert('Gagal hapus');
        }
    });

});

// ======================
// INIT
// ======================
loadUraian();
loadSub();

</script>

</body>
</html>
