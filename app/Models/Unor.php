<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unor extends Model
{
    protected $table = 'unor'; // Sesuaikan dengan nama tabel di database kamu

    protected $primaryKey = 'unorId'; // Sesuaikan jika primary key bukan 'id'

    public $incrementing = false; // Karena unorId mungkin bukan auto-increment
    protected $keyType = 'string'; // Jika unorId berupa string UUID

    public $timestamps = false; // Kalau tidak ada created_at dan updated_at
}
