<!DOCTYPE html>
<html>
<head>
    <title>Kategori</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            background: linear-gradient(120deg, #f8fbff 0%, #eef5ff 100%);
            font-family: 'Segoe UI', sans-serif;
        }

        .top-bar {
            background: linear-gradient(90deg, #0d6efd, #1e88e5);
            color: white;
            padding: 20px;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 20px rgba(13,110,253,0.2);
        }

        .card-glass {
            background: white;
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .title {
            font-weight: 700;
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
</head>

<body>

<!-- HEADER -->
<div class="top-bar mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <h4 class="title mb-0">📁 MASTER KATEGORI</h4>
        <span class="badge-total">
            Total: {{ count($kategoris) }}
        </span>
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
        <div class="col-md-4 mb-3">
            <div class="card card-glass p-4">

                <h5 class="mb-3 text-primary">Tambah Kategori</h5>

                <form id="formKategori">
                    <input type="text" name="nama_kategori"
                           class="form-control mb-3"
                           placeholder="Masukkan nama kategori..." required>

                    <button class="btn btn-primary w-100">
                        + Simpan Kategori
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
                            <th class="text-center">Aksi</th>
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
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
// setup CSRF
$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});

// TAMBAH
$('#formKategori').submit(function(e){
    e.preventDefault();

    $.post('/inspeksi/master/kategori', $(this).serialize(), function(){
        location.reload();
    });
});

// DELETE
$(document).on('click', '.btn-delete', function(){

    let id = $(this).data('id');

    if(!confirm('Yakin mau hapus data ini?')) return;

    $.ajax({
        url: '/inspeksi/kategori-delete/' + id,
        type: 'DELETE',
        success: function(){
            $('#row-'+id).fadeOut(300, function(){
                $(this).remove();
            });
            alert('✅ Berhasil dihapus');
        },
        error: function(xhr){
            console.log(xhr.responseText);
            alert('❌ Gagal hapus (cek route/controller)');
        }
    });

});
</script>

</body>
</html>
