<!DOCTYPE html>
<html>
<head>
    <title>Data Pegawai</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Data Pegawai</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pegawai</th>
                <th>NIP</th>
                <th>NIP Lama</th>
                <th>Nama</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($pegawai as $i => $pegawai)
                <tr>
                <td>{{  $i + 1  }}</td>
                <td>{{ $pegawai->idPegawai }}</td>
                <td>{{ $pegawai->nip }}</td>
                <td>{{ $pegawai->nipLama }}</td>
                <td>{{ $pegawai->nama }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
