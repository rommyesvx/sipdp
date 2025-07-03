<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie;

class Feedback extends Model
{
    use LogsActivity;
    protected $table = 'feedback';
    
    protected $fillable = ['user_id', 'permohonan_id', 'pesan', 'rating'];

    protected static $recordEvents = ['created', 'deleted'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permohonan()
    {
        return $this->belongsTo(PermohonanData::class, 'permohonan_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
         return LogOptions::defaults()
            // 1. Tambahkan 'catatan_evaluasi' ke dalam kolom yang dicatat
            ->logOnly(['user_id', 'permohonan_id', 'pesan', 'rating', 'catatan_evaluasi'])
            
            // 2. Beri deskripsi yang lebih spesifik untuk event 'created'
            ->setDescriptionForEvent(function(string $eventName) {
                if ($eventName === 'created') {
                    return "User memberikan feedback baru.";
                }
                 return "Sebuah feedback telah di-{$eventName}.";
                
            })
            ->logOnlyDirty()
            
            ->useLogName('feedback');
    }
    
}
