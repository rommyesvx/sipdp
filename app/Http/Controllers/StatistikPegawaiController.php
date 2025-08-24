<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class StatistikPegawaiController extends Controller
{
    public function index()
    {
        // 1. Data untuk Chart Distribusi Pendidikan
        $dataPendidikan = Pegawai::select('tkPendidikanTerakhir as tingkat_pendidikan', DB::raw('count(*) as total'))
            ->whereNotNull('tkPendidikanTerakhir')
            ->groupBy('tingkat_pendidikan')
            ->orderBy('total', 'desc') // Urutkan dari yang terbanyak
            ->pluck('total', 'tingkat_pendidikan');

        $pendidikanLabels = $dataPendidikan->keys();
        $pendidikanData = $dataPendidikan->values();

        // 2. Data untuk Chart Sebaran Usia
        $dataUsia = Pegawai::select(
                DB::raw("CASE
                    WHEN TIMESTAMPDIFF(YEAR, tglLahir, CURDATE()) < 30 THEN '< 30 Tahun'
                    WHEN TIMESTAMPDIFF(YEAR, tglLahir, CURDATE()) BETWEEN 30 AND 39 THEN '30-39 Tahun'
                    WHEN TIMESTAMPDIFF(YEAR, tglLahir, CURDATE()) BETWEEN 40 AND 49 THEN '40-49 Tahun'
                    WHEN TIMESTAMPDIFF(YEAR, tglLahir, CURDATE()) >= 50 THEN '>= 50 Tahun'
                    ELSE 'Tidak Diketahui'
                END as kelompok_usia"),
                DB::raw('count(*) as total')
            )
            ->whereNotNull('tglLahir')
            ->groupBy('kelompok_usia')
            ->pluck('total', 'kelompok_usia');

        // Mengurutkan label usia secara manual agar tampilannya logis
        $usiaLabels = ['< 30 Tahun', '30-39 Tahun', '40-49 Tahun', '>= 50 Tahun'];
        $usiaData = collect($usiaLabels)->map(function ($label) use ($dataUsia) {
            return $dataUsia->get($label, 0); // Ambil data, jika tidak ada maka 0
        });

        // 3. Data untuk Chart Jenis Kelamin
        $dataJenisKelamin = Pegawai::select('jenisKelamin', DB::raw('count(*) as total'))
            ->whereNotNull('jenisKelamin')
            ->where('jenisKelamin', '!=', '') // Menghindari data kosong
            ->groupBy('jenisKelamin')
            ->pluck('total', 'jenisKelamin');

        $jenisKelaminLabels = $dataJenisKelamin->keys();
        $jenisKelaminData = $dataJenisKelamin->values();

        // 4. Data untuk Chart Golongan/Pangkat
        $dataGolongan = Pegawai::select('golRuangAkhir', DB::raw('count(*) as total'))
            ->whereNotNull('golRuangAkhir')
            ->where('golRuangAkhir', '!=', '') // Menghindari data kosong
            ->groupBy('golRuangAkhir')
            ->orderBy('golRuangAkhir', 'asc') // Diurutkan berdasarkan teks golongan
            ->pluck('total', 'golRuangAkhir');

        $golonganLabels = $dataGolongan->keys();
        $golonganData = $dataGolongan->values();

        // 5. Data untuk Chart Masa Kerja
        $queryMasaKerja = "
            SELECT
                CASE
                    WHEN total_bulan < 12 THEN 'Kurang dari 1 Tahun'
                    WHEN total_bulan BETWEEN 12 AND 60 THEN '1 - 5 Tahun'
                    WHEN total_bulan BETWEEN 61 AND 120 THEN '6 - 10 Tahun'
                    WHEN total_bulan BETWEEN 121 AND 180 THEN '11 - 15 Tahun'
                    WHEN total_bulan BETWEEN 181 AND 240 THEN '16 - 20 Tahun'
                    ELSE 'Lebih dari 20 Tahun'
                END AS kelompok_masa_kerja,
                COUNT(*) as total
            FROM
                (SELECT
                    -- Mengambil angka tahun dan bulan dari string 'X tahun Y bulan'
                    (CAST(SUBSTRING_INDEX(masaKerja, ' ', 1) AS UNSIGNED) * 12) +
                    CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(masaKerja, ' ', 4), ' ', -1) AS UNSIGNED)
                    AS total_bulan
                FROM pegawai
                WHERE masaKerja IS NOT NULL AND masaKerja != '' AND masaKerja LIKE '%tahun%bulan%') AS subquery
            GROUP BY kelompok_masa_kerja
        ";

        $dataMasaKerja = collect(DB::select($queryMasaKerja))->pluck('total', 'kelompok_masa_kerja');

        // Mengurutkan label masa kerja secara manual
        $masaKerjaLabels = ['Kurang dari 1 Tahun', '1 - 5 Tahun', '6 - 10 Tahun', '11 - 15 Tahun', '16 - 20 Tahun', 'Lebih dari 20 Tahun'];
        $masaKerjaData = collect($masaKerjaLabels)->map(function ($label) use ($dataMasaKerja) {
            return $dataMasaKerja->get($label, 0);
        });

        $data = compact(
            'pendidikanLabels',
            'pendidikanData',
            'usiaLabels',
            'usiaData',
            'jenisKelaminLabels', 
            'jenisKelaminData',   
            'golonganLabels',     
            'golonganData',
            'masaKerjaLabels',
            'masaKerjaData'
        );

        if (auth()->user()->role === 'kepala') { 
            return view('kepala.statistik-pegawai.index', $data);
        }

        return view('admin.statistik-pegawai.index', $data);
    }
}
