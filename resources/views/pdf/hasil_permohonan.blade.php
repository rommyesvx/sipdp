<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Permohonan Data</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
        }

        .header-table,
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 6px;
        }

        .main-table th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        h3 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h3>Laporan Permohonan Data</h3>
    <table class="header-table" style="margin-bottom: 20px;">
        <tr>
            <td style="width: 15%;">Periode Tanggal</td>
            <td style="width: 85%;">: {{ request('tanggal_mulai', 'Semua') }} s/d {{ request('tanggal_akhir', 'Semua') }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>: {{ ucfirst(request('status', 'Semua')) }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                @foreach($kolomTampil as $kolom)
                <th>{{ $columnLabels[$kolom] ?? ucfirst(str_replace('_', ' ', $kolom)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($pegawais as $pegawai)
            <tr>
                @foreach($kolomTampil as $kolom)
                <td>{{ $pegawai->{$kolom} }}</td>
                @endforeach
            </tr>
            @empty
            <tr>
                <td colspan="{{ count($kolomTampil) }}" style="text-align: center;">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>