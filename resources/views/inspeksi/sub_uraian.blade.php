@extends('layouts.app')

@section('title', 'Sub Uraian')

@section('content')

<div class="container-fluid">

    <h4 class="mb-4">🧩 MASTER SUB URAIAN</h4>

    <div class="row">

        <!-- FORM -->
        <div class="col-md-4">
            <div class="card shadow p-4">

                <h5 class="text-primary mb-3">Tambah Sub Uraian</h5>

                <label>Pilih Uraian</label>
                <select id="uraian" class="form-select mb-3"></select>

                <label>Nama Sub Uraian</label>
                <input type="text" id="nama_sub"
                       class="form-control mb-2"
                       placeholder="Ketik lalu tekan ENTER">

                <!-- PREVIEW -->
                <div id="previewList" class="mb-3"></div>

                <button id="btnSub" class="btn btn-primary w-100">
                    + Simpan Semua
                </button>

            </div>
        </div>

        <!-- LIST -->
        <div class="col-md-8">
            <div class="card shadow p-4">

                <h5 class="text-primary mb-3">Daftar Sub Uraian</h5>

                <div id="listSub"></div>

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

let subList = [];

// ENTER = TAMBAH LIST
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

// PREVIEW
function renderPreview(){

    let html = '';

    subList.forEach((item,index)=>{
        html += `
        <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-2">
            <span>${item}</span>
            <button class="btn btn-sm btn-danger" onclick="removeItem(${index})">x</button>
        </div>`;
    });

    $('#previewList').html(html);
}

// HAPUS DARI LIST
function removeItem(index){
    subList.splice(index,1);
    renderPreview();
}

// LOAD URAIAN
function loadUraian(){

    $.get('/inspeksi/get-uraian-all', function(data){

        let opt = '<option value="">Pilih Uraian</option>';

        data.forEach(u=>{
            opt += `<option value="${u.id}">${u.nama_uraian}</option>`;
        });

        $('#uraian').html(opt);
    });

}

// LOAD SUB
function loadSub(){

    $.get('/inspeksi/get-sub-all', function(data){

        let html = '';

        data.forEach(s=>{
            html += `
            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                <div>${s.nama_sub_uraian}</div>
                <button class="btn btn-sm btn-danger btn-hapus" data-id="${s.id}">
                    Hapus
                </button>
            </div>`;
        });

        $('#listSub').html(html);
    });

}

// SIMPAN MULTI
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
    },function(){

        subList = [];
        renderPreview();
        loadSub();

    }).fail(function(xhr){
        console.log(xhr.responseText);
        alert('Gagal simpan');
    });

});

// DELETE
$(document).on('click','.btn-hapus',function(){

    let id = $(this).data('id');

    if(!confirm('Yakin hapus?')) return;

    $.ajax({
        url: '/suburaian/' + id,
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

@endsection
