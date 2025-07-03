<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'pegawai';
    protected $primaryKey = 'idPegawai';
    protected $fillable = [
        // 'periode_data',
    ];

    public function unorInduk(){
        return $this->belongsTo(Unor::class, 'unorIndukId', 'unorId');
    }
}
