<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unor extends Model
{
    protected $table = 'unor';

    protected $primaryKey = 'unorId'; 

    public $incrementing = false;
    protected $keyType = 'string'; 

    public $timestamps = false;
}
