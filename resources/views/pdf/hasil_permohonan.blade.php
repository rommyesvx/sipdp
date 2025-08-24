<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Hasil Data Pegawai - Dokumen Resmi</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            position: relative;
        }

        .header-table,
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .main-table th {
            background-color: #f0f0f0;
        }

        .text-center {
            text-align: center;
        }

        h3,
        h4 {
            text-align: center;
            margin: 4px 0;
        }

        .logo {
            width: 60px;
            float: left;
        }

        .instansi-info {
            text-align: center;
        }

        .watermark {
            position: fixed;
            top: 35%;
            left: 25%;
            opacity: 0.08;
            font-size: 70px;
            transform: rotate(-30deg);
            color: red;
            z-index: -1;
        }

        .footer {
            position: fixed;
            bottom: 0;
            font-size: 8px;
            width: 100%;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 3px;
        }

        .doc-info {
            font-size: 9px;
            margin-bottom: 10px;
        }

        .signature {
            margin-top: 30px;
            width: 100%;
        }

        .signature td {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="watermark">DOKUMEN RESMI</div>

    <!-- Header dengan logo dan informasi instansi -->
    <table class="header-table" style="margin-bottom: 10px;">
        <tr>
            <td style="width: 15%;">
                
            </td>
            <td class="instansi-info">
                <h4>PEMERINTAH KOTA BLITAR</h4>
                <h3>BADAN KEPEGAWAIAN DAN SUMBER DAYA MANUSIA</h3>
                <div style="font-size: 9px;">
                    Jl. Sudanco Supriyadi No.1, Blitar<br>
                    Telp: (0342) 123456 - Email: kepegawaian@blitarkota.go.id
                </div>
            </td>
        </tr>
    </table>

    <hr style="margin-bottom: 10px;">

    <!-- Informasi dokumen -->
        <strong>Nomor Dokumen:</strong> DOC-{{ now()->format('YmdHis') }}<br>
        <strong>Tanggal Cetak:</strong> {{ now()->format('d-m-Y H:i') }}
    </div>

    <h3>Hasil Permohonan Data Pegawai</h3>
    <p><strong>Kolom Data Diminta:</strong>
        @foreach($kolomTampil as $kolom)
        <span style="background:#e9ecef; border-radius:8px; padding:2px 8px; margin-right:3px;">
            {{ $columnLabels[$kolom] ?? ucfirst(str_replace('_', ' ', $kolom)) }}
        </span>
        @endforeach
    </p>
    @if(is_array($jenisData))
    <p><strong>Jenis Data Diminta:</strong></p>
    <ul>
        @foreach($jenisData as $item)
        <li>
            <strong>{{ $item['kriteria'] ?? '-' }}:</strong>
            {{ $item['nilai'] ?? '-' }}
        </li>
        @endforeach
    </ul>
    @elseif(is_string($jenisData))
    <p><strong>Jenis Data Diminta:</strong> {{ $jenisData }}</p>
    @endif

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
                <td colspan="{{ count($kolomTampil) }}" class="text-center">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda tangan (opsional) -->
    <table class="signature">
        <tr>
            <td style="width: 50%;"></td>
            <td>
                Blitar, {{ now()->format('d F Y') }}<br>
                Kepala Bidang SDM<br><br><br><br>
                <u><strong>Drs. Ahmad Subekti</strong></u><br>
                NIP. 19650404 198703 1 002
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        Dokumen ini dicetak melalui SIPDP – Sistem Informasi Permohonan Data Pegawai. Dilarang menyebarluaskan tanpa izin tertulis.
    </div>
</body>

</html>