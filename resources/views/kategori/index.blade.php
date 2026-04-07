<!DOCTYPE html>
<html>
<head>
    <title>Master Data Kategori / Uraian / Sub-Uraian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">

    <h2>Master Data Kategori / Uraian / Sub-Uraian</h2>

    {{-- ========================= --}}
    {{-- 1. Tambah Kategori --}}
    {{-- ========================= --}}
    <form action="{{ route('master.store', ['type' => 'kategori']) }}" method="POST" class="mb-3">
        @csrf
        <div class="input-group mb-2">
            <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori" required>
            <button class="btn btn-primary" type="submit">Tambah Kategori</button>
        </div>
    </form>

    {{-- ========================= --}}
    {{-- 2. Tambah Uraian --}}
    {{-- ========================= --}}
    <form action="{{ route('master.store', ['type' => 'uraian']) }}" method="POST" class="mb-3">
        @csrf
        <div class="input-group mb-2">
            <select name="kategori_id" class="form-select" required>
                <option value="">Pilih Kategori</option>
                @forelse($kategoris as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                @empty
                    <option value="">Belum ada kategori</option>
                @endforelse
            </select>
            <input type="text" name="nama_uraian" class="form-control" placeholder="Nama Uraian" required>
            <button class="btn btn-primary" type="submit">Tambah Uraian</button>
        </div>
    </form>

    {{-- ========================= --}}
    {{-- 3. Tambah Sub-Uraian --}}
    {{-- ========================= --}}
    <form action="{{ route('master.store', ['type' => 'suburaian']) }}" method="POST" class="mb-3">
        @csrf
        <div class="input-group mb-2">
            <select name="uraian_id" class="form-select" required>
                <option value="">Pilih Uraian</option>
                @foreach($kategoris as $k)
                    @foreach($k->uraian as $u)
                        <option value="{{ $u->id }}">{{ $k->nama_kategori }} → {{ $u->nama_uraian }}</option>
                    @endforeach
                @endforeach
            </select>
            <input type="text" name="nama_sub_uraian" class="form-control" placeholder="Nama Sub-Uraian" required>
            <button class="btn btn-primary" type="submit">Tambah Sub-Uraian</button>
        </div>
    </form>

    <hr>

    <h4>Data Master Saat Ini</h4>
    @forelse($kategoris as $k)
        <div class="mb-3">
            <strong>{{ $k->nama_kategori }}</strong>
            @if($k->uraian->isNotEmpty())
                <ul>
                    @foreach($k->uraian as $u)
                        <li>{{ $u->nama_uraian }}
                            @if($u->subUraian->isNotEmpty())
                                <ul>
                                    @foreach($u->subUraian as $s)
                                        <li>{{ $s->nama_sub_uraian }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <ul><li><em>Belum ada sub-uraian</em></li></ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p><em>Belum ada uraian</em></p>
            @endif
        </div>
    @empty
        <p><em>Belum ada kategori</em></p>
    @endforelse

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>