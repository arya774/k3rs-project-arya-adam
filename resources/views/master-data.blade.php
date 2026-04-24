<!DOCTYPE html>
<html>
<head>
    <title>Master Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<h3 class="mb-4">MASTER DATA</h3>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- 🔥 MENU PILIHAN -->
<div class="mb-4 text-center">
    <button class="btn btn-outline-primary me-2" onclick="showMenu('kategori')">Kategori</button>
    <button class="btn btn-outline-success me-2" onclick="showMenu('uraian')">Uraian</button>
    <button class="btn btn-outline-warning" onclick="showMenu('sub')">Sub Uraian</button>
</div>

<div class="row justify-content-center">

<!-- ================= KATEGORI ================= -->
<div class="col-md-6 menu-content" id="menu-kategori">
<div class="card shadow">
    <div class="card-header bg-primary text-white">Kategori</div>
    <div class="card-body">

        <form method="POST" action="/inspeksi/master-data/kategori">
            @csrf
            <input type="text" name="nama" class="form-control mb-2" placeholder="Nama Kategori">
            <button class="btn btn-primary w-100 mb-3">Tambah</button>
        </form>

        <!-- 🔥 TABEL -->
        <table class="table table-bordered">
            <tr><th>No</th><th>Nama</th></tr>
            @foreach($kategori as $i => $k)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $k->nama }}</td>
            </tr>
            @endforeach
        </table>

    </div>
</div>
</div>

<!-- ================= URAIAN ================= -->
<div class="col-md-6 menu-content d-none" id="menu-uraian">
<div class="card shadow">
    <div class="card-header bg-success text-white">Uraian</div>
    <div class="card-body">

        <form method="POST" action="/inspeksi/master-data/uraian">
            @csrf

            <select name="kategori_id" id="kategori" class="form-control mb-2">
                <option value="">Pilih Kategori</option>
                @foreach($kategori as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>

            <input type="text" name="nama" class="form-control mb-2" placeholder="Nama Uraian">

            <button class="btn btn-success w-100 mb-3">Tambah</button>
        </form>

        <!-- 🔥 TABEL -->
        <table class="table table-bordered">
            <tr><th>No</th><th>Kategori</th><th>Uraian</th></tr>
            @foreach($uraian as $i => $u)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $u->kategori_id }}</td>
                <td>{{ $u->nama }}</td>
            </tr>
            @endforeach
        </table>

    </div>
</div>
</div>

<!-- ================= SUB URAIAN ================= -->
<div class="col-md-6 menu-content d-none" id="menu-sub">
<div class="card shadow">
    <div class="card-header bg-warning">Sub Uraian</div>
    <div class="card-body">

        <form method="POST" action="/inspeksi/master-data/sub-uraian">
            @csrf

            <select name="uraian_id" id="uraian" class="form-control mb-2">
                <option value="">Pilih Uraian</option>
            </select>

            <input type="text" name="nama" class="form-control mb-2" placeholder="Nama Sub Uraian">

            <button class="btn btn-warning w-100 mb-3">Tambah</button>
        </form>

        <!-- 🔥 TABEL -->
        <table class="table table-bordered">
            <tr><th>No</th><th>Uraian</th><th>Sub Uraian</th></tr>
            @foreach($subUraian as $i => $s)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $s->uraian_id }}</td>
                <td>{{ $s->nama }}</td>
            </tr>
            @endforeach
        </table>

    </div>
</div>
</div>

</div>

<!-- 🔥 SWITCH MENU -->
<script>
function showMenu(menu) {
    document.querySelectorAll('.menu-content').forEach(el => el.classList.add('d-none'));
    document.getElementById('menu-' + menu).classList.remove('d-none');
}
</script>

<!-- 🔥 AJAX -->
<script>
document.getElementById('kategori').addEventListener('change', function() {
    let kategoriId = this.value;

    fetch(`/inspeksi/get-uraian/${kategoriId}`)
        .then(res => res.json())
        .then(data => {
            let uraianSelect = document.getElementById('uraian');
            uraianSelect.innerHTML = '<option value="">Pilih Uraian</option>';

            data.forEach(item => {
                uraianSelect.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
            });
        });
});
</script>

</body>
</html>
