<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PermohonanDataExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;
    protected $kolomTampil;
    protected $columnLabels;

    public function __construct($data, $kolomTampil, $columnLabels)
    {
        $this->data = $data;
        $this->kolomTampil = $kolomTampil;
        $this->columnLabels = $columnLabels;
    }

    // Method ini akan mengisi data baris
    public function collection()
    {
        return $this->data;
    }

    // Method ini akan membuat header kolom
    public function headings(): array
    {
        $headers = [];
        foreach ($this->kolomTampil as $kolom) {
            // Ambil label dari kamus, atau gunakan nama kolom asli
            $headers[] = $this->columnLabels[$kolom] ?? ucfirst($kolom);
        }
        return $headers;
    }

    // Method ini memetakan setiap baris data agar sesuai urutan header
    public function map($row): array
    {
        $mappedRow = [];
        foreach ($this->kolomTampil as $kolom) {
            $mappedRow[] = $row->{$kolom};
        }
        return $mappedRow;
    }
}
