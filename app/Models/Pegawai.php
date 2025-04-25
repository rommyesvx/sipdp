<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'pegawai'; // Sesuaikan dengan nama tabel di database
    protected $primaryKey = 'idPegawai'; // Sesuaikan jika primary key berbeda
    protected $fillable = [
        'periode_data',
    ];

    public function unorInduk(){
        return $this->belongsTo(Unor::class, 'unorIndukId', 'unorId');
    }
}
