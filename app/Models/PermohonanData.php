<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PermohonanData extends Model
{
    use HasFactory, LogsActivity; 

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
        'alasan_penolakan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'permohonan_id');
    }

   
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'file_hasil']) 
            ->logOnlyDirty()
            ->useLogName('permohonan_data');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Permohonan data telah {$eventName}";
    }
}
