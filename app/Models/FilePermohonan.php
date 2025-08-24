<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilePermohonan extends Model
{
    use HasFactory;
    protected $table = 'file_permohonan';
    protected $fillable = ['permohonan_data_id', 'tipe_file', 'path', 'nama_asli_file'];

    public function permohonanData()
    {
        return $this->belongsTo(PermohonanData::class);
    }
}