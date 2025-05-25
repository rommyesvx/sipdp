<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index()
    {
        $katalog = [
            [
                'nama' => 'Data Pegawai Aktif',
                'deskripsi' => 'Berisi daftar pegawai yang masih aktif bekerja.',
                'format' => 'Excel (.xlsx)',
                'frekuensi' => 'Bulanan',
                'akses' => 'Terbatas',
            ],
            [
                'nama' => 'Riwayat Jabatan Pegawai',
                'deskripsi' => 'Rekam jejak jabatan struktural dan fungsional pegawai.',
                'format' => 'PDF',
                'frekuensi' => 'Tahunan',
                'akses' => 'Terbatas',
            ],
            [
                'nama' => 'Statistik Pegawai Berdasarkan Gender',
                'deskripsi' => 'Statistik jumlah pegawai laki-laki dan perempuan.',
                'format' => 'CSV',
                'frekuensi' => 'Bulanan',
                'akses' => 'Publik',
            ],
        ];

        return view('users.katalog.index', compact('katalog'));
    }
}
