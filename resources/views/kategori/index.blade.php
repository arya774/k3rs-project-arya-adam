<!DOCTYPE html>
<html>
<head>
    <title>Master Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">

    <h2 class="mb-4">Master Data Kategori / Uraian / Sub-Uraian</h2>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ================= TAMBAH KATEGORI ================= --}}
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">Tambah Kategori</div>
        <div class="card-body">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori" required>
                    <button class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= TAMBAH URAIAN ================= --}}
    <div class="card mb-3">
        <div class="card-header bg-success text-white">Tambah Uraian</div>
        <div class="card-body">
            <form action="{{ route('uraian.store') }}" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col-md-4">
                        <select name="kategori_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <input type="text" name="nama_uraian" class="form-control" placeholder="Nama Uraian" required>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-success w-100">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= TAMBAH SUB URAIAN ================= --}}
    <div class="card mb-3">
        <div class="card-header bg-warning">Tambah Sub Uraian</div>
        <div class="card-body">
            <form action="{{ route('suburaian.store') }}" method="POST">
                @csrf
                <div class="row g-2">

                    <div class="col-md-4">
                        <select name="uraian_id" class="form-select" required>
                            <option value="">Pilih Uraian</option>
                            @foreach($kategoris as $k)
                                @foreach($k->uraian as $u)
                                    <option value="{{ $u->id }}">
                                        {{ $k->nama_kategori }} → {{ $u->nama_uraian }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <input type="text" name="nama_sub_uraian" class="form-control" placeholder="Nama Sub Uraian" required>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-warning w-100">Tambah</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- ================= DATA MASTER ================= --}}
    <div class="card">
        <div class="card-header bg-dark text-white">Data Master</div>
        <div class="card-body">

            @forelse($kategoris as $k)
                <div class="mb-3 border rounded p-3">

                    <h5 class="text-primary">{{ $k->nama_kategori }}</h5>

                    @forelse($k->uraian as $u)
                        <div class="ms-3">

                            <strong>• {{ $u->nama_uraian }}</strong>

                            @if($u->subUraian->isNotEmpty())
                                <ul class="ms-3">
                                    @foreach($u->subUraian as $s)
                                        <li>{{ $s->nama_sub_uraian }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted ms-3">Belum ada sub-uraian</p>
                            @endif

                        </div>
                    @empty
                        <p class="text-muted ms-3">Belum ada uraian</p>
                    @endforelse

                </div>
            @empty
                <p class="text-muted">Belum ada data</p>
            @endforelse

        </div>
    </div>

</div>
</body>
</html>