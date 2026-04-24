@extends('layouts.app')

@section('title','Kategori')

@section('content')

<style>
    .top-bar {
        background: linear-gradient(90deg, #0d6efd, #1e88e5);
        color: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(13,110,253,0.2);
        margin-bottom: 20px;
    }

    .card-glass {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        border: none;
    }

    .btn-primary {
        background: linear-gradient(90deg, #0d6efd, #1e88e5);
        border: none;
        border-radius: 10px;
    }

    .table thead {
        background: #0d6efd;
        color: white;
    }

    .table tbody tr:hover {
        background: #f1f7ff;
    }

    .badge-total {
        background: white;
        color: #0d6efd;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 20px;
    }
</style>

<!-- HEADER -->
<div class="top-bar d-flex justify-content-between align-items-center">
    <h4 class="mb-0">📁 MASTER KATEGORI</h4>
    <span class="badge-total">
        Total: {{ count($kategoris) }}
    </span>
</div>

<div class="row">

    <!-- FORM -->
    <div class="col-md-4 mb-3">
        <div class="card card-glass p-4">

            <h5 class="mb-3 text-primary">Tambah Kategori</h5>

            <form id="formKategori">
                <input type="text" name="nama_kategori"
                       class="form-control mb-3"
                       placeholder="Masukkan nama kategori..." required>

                <button class="btn btn-primary w-100">
                    + Simpan
                </button>
            </form>

        </div>
    </div>

    <!-- TABLE -->
    <div class="col-md-8">
        <div class="card card-glass p-4">

            <h5 class="mb-3 text-primary">Daftar Kategori</h5>

            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th class="text-center" width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody id="tableKategori">
                    @foreach($kategoris as $k)
                    <tr id="row-{{ $k->id }}">
                        <td>{{ $k->nama_kategori }}</td>
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm btn-delete"
                                    data-id="{{ $k->id }}">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

</div>

<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
// CSRF
$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});

// TAMBAH TANPA RELOAD
$('#formKategori').submit(function(e){
    e.preventDefault();

    let form = $(this);

    $.post('/inspeksi/master/kategori', form.serialize(), function(){

        let nama = form.find('input[name="nama_kategori"]').val();

        $('#tableKategori').append(`
            <tr>
                <td>${nama}</td>
                <td class="text-center">
                    <span class="text-success">✔</span>
                </td>
            </tr>
        `);

        form[0].reset();

    }).fail(function(xhr){
        console.log(xhr.responseText);
        alert('❌ Gagal simpan');
    });
});

// DELETE
$(document).on('click', '.btn-delete', function(){

    let id = $(this).data('id');

    if(!confirm('Yakin hapus data ini?')) return;

    $.ajax({
        url: '/inspeksi/kategori-delete/' + id,
        type: 'DELETE',
        success: function(){
            $('#row-'+id).fadeOut(200, function(){
                $(this).remove();
            });
        },
        error: function(xhr){
            console.log(xhr.responseText);
            alert('❌ Gagal hapus');
        }
    });

});
</script>

@endsection
