@extends('layouts.app')

@section('title', 'Uraian')

@section('content')

<div class="container-fluid">

    <h4 class="mb-4">📋 MASTER URAIAN</h4>

    <div class="row">

        <!-- FORM -->
        <div class="col-md-4">
            <div class="card shadow p-4">

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

        <!-- TABLE -->
        <div class="col-md-8">
            <div class="card shadow p-4">

                <h5 class="text-primary mb-3">Daftar Uraian</h5>

                <table class="table align-middle">
                    <thead class="table-primary">
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

// TAMBAH URAIAN
$('#btnUraian').click(function(){

    let kategori = $('#kategori').val();
    let nama = $('#nama_uraian').val();

    if(!kategori){
        alert('Pilih kategori dulu!');
        return;
    }

    if(!nama){
        alert('Nama tidak boleh kosong!');
        return;
    }

    $.post('/inspeksi/master/uraian', {
        kategori_id: kategori,
        nama_uraian: nama
    })
    .done(function(){
        $('#nama_uraian').val('');
        loadUraian();
    });

});

// LOAD DATA
function loadUraian(){

    $.get('/inspeksi/get-uraian-all', function(data){

        let html = '';

        data.forEach(u => {
            html += `
                <tr>
                    <td>${u.kategori.nama_kategori}</td>
                    <td>${u.nama_uraian}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${u.id}">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;
        });

        $('#tableUraian').html(html);
    });

}

// DELETE
$(document).on('click','.btn-delete',function(){

    let id = $(this).data('id');

    if(!confirm('Hapus data?')) return;

    $.ajax({
        url: '/inspeksi/uraian-delete/' + id,
        type: 'DELETE',
        success: function(){
            loadUraian();
        }
    });

});

// INIT
loadUraian();

</script>

@endsection
