<!DOCTYPE html>
<html>
<head>
    <title>Laporan Permohonan Data</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Laporan Permohonan Data</h2>
    <p>Periode: {{ request('tanggal_mulai') }} s/d {{ request('tanggal_akhir') }}</p>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Pemohon</th><th>Asal</th><th>Tujuan</th><th>Status</th><th>Tgl Diajukan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permohonans as $permohonan)
            <tr>
                <td>#{{ $permohonan->id }}</td>
                <td>{{ $permohonan->user->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($permohonan->asal) }}</td>
                <td>{{ $permohonan->tujuan }}</td>
                <td>{{ ucfirst($permohonan->status) }}</td>
                <td>{{ $permohonan->created_at->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>