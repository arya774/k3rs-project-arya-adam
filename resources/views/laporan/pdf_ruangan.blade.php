<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .info {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th {
            background: #eee;
            padding: 6px;
        }

        td {
            padding: 5px;
        }

        .badge-ya {
            color: green;
            font-weight: bold;
        }

        .badge-tidak {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>

<h2>LAPORAN INSPEKSI K3</h2>

<div class="info">
    <b>Ruangan:</b> {{ $ruangan }} <br>
    <b>Tanggal Cetak:</b> {{ date('Y-m-d') }}
</div>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Uraian</th>
            <th>Sub Uraian</th>
            <th>Nilai</th>
            <th>Catatan</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $row)
        <tr>
            <td>{{ $row->tanggal }}</td>
            <td>{{ $row->nama_kategori }}</td>
            <td>{{ $row->nama_uraian }}</td>
            <td>{{ $row->nama_sub_uraian }}</td>

            <td>
                <span class="{{ $row->nilai == 'ya' ? 'badge-ya' : 'badge-tidak' }}">
                    {{ strtoupper($row->nilai) }}
                </span>
            </td>

            <td>{{ $row->catatan }}</td>
        </tr>
        @endforeach
    </tbody>

</table>

</body>
</html>