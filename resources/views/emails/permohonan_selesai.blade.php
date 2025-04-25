<!DOCTYPE html>
<html>
<head>
    <title>Permohonan Selesai</title>
</head>
<body>
    <p>Halo {{ $permohonan->user->name }},</p>
    <p>Permohonan data pegawai Anda dengan ID <strong>{{ $permohonan->id }}</strong> telah selesai diproses.</p>
    <p>Silakan login ke sistem untuk melihat detailnya.</p>
    <p>Terima kasih.</p>
</body>
</html>
