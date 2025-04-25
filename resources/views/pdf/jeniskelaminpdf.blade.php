<!DOCTYPE html>
<html>
<head>
    <title>Data Jenis Kelamin</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 6px;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h3>Data Pegawai Berdasarkan Jenis Kelamin</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pegawai as $i => $pegawai)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $pegawai->nama }}</td>
                    <td>{{ $pegawai->jenisKelamin }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
