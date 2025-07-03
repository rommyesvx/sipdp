<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPermohonanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'ID Permohonan',
            'Nama Pemohon',
            'Asal Instansi',
            'Tujuan',
            'Jenis Data',
            'Status',
            'Tanggal Diajukan',
        ];
    }

    public function map($permohonan): array
    {
        return [
            $permohonan->id,
            $permohonan->user->name ?? 'N/A',
            ucfirst($permohonan->asal),
            $permohonan->tujuan,
            \Illuminate\Support\Str::isJson($permohonan->jenis_data) ? 'Sesuai Kriteria' : 'Kustom',
            ucfirst($permohonan->status),
            $permohonan->created_at->format('d-m-Y H:i'),
        ];
    }
}
