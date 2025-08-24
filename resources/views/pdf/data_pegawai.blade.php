<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pegawai</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; }
        .main-table { width: 100%; border-collapse: collapse; }
        .main-table th, .main-table td { border: 1px solid #333; padding: 6px; text-align: left; }
        .main-table th { background-color: #f2f2f2; font-weight: bold; }
        h3 { text-align: center; }
        .header-info { margin-bottom: 20px; font-size: 11px; }
    </style>
</head>
<body>
    <h3>Laporan Data Pegawai</h3>
    <div class="header-info">
        Dicetak pada: {{ $tanggalCetak }}
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th>NIP</th>
                <th>Nama</th>
                <th>Departemen</th>
                <th>Nama Jabatan</th>
                <th>Golongan</th>
                <th>Pendidikan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pegawais as $pegawai)
            <tr>
                <td>{{ $pegawai->nipBaru }}</td>
                <td>{{ $pegawai->nama }}</td>
                <td>{{ $pegawai->satuanKerjaKerjaNama }}</td>
                <td>{{ $pegawai->jabatanNama }}</td>
                <td>{{ $pegawai->golRuangAkhir }}</td>
                <td>{{ $pegawai->pendidikanTerakhirNama }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
