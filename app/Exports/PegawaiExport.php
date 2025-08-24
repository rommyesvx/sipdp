<?php

namespace App\Exports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class PegawaiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $pegawais;

    /**
     * Menerima data pegawai yang sudah difilter dari controller.
     *
     * @param Collection $pegawais
     */
    public function __construct(Collection $pegawais)
    {
        $this->pegawais = $pegawais;
    }

    /**
     * Mengembalikan koleksi data yang akan diekspor.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->pegawais;
    }

    /**
     * Menentukan header untuk kolom di file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Departemen',
            'Nama Jabatan',
            'Golongan',
            'Pendidikan Terakhir',
        ];
    }

    /**
     * Memetakan data dari setiap model Pegawai ke baris Excel.
     * Ini memastikan urutan kolom sesuai dengan header.
     *
     * @param mixed $pegawai
     * @return array
     */
    public function map($pegawai): array
    {
        return [
            $pegawai->nipBaru,
            $pegawai->nama,
            $pegawai->satuanKerjaKerjaNama,
            $pegawai->jabatanNama,
            $pegawai->golRuangAkhir,
            $pegawai->pendidikanTerakhirNama,
        ];
    }
}