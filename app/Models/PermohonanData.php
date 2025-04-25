<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermohonanData extends Model
{
    use HasFactory;

    protected $table = 'data_permohonan';

    protected $fillable = [
        'user_id',
        'tujuan',
        'tipe',
        'jenis_data',
        'file_permohonan',
        'catatan',
        'status',
        'file_hasil',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'permohonan_id');
    }

}
