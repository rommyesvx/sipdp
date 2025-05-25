<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Feedback extends Model
{
    use LogsActivity;
    protected $table = 'feedback';
    
    protected $fillable = ['user_id', 'permohonan_id', 'pesan', 'rating'];

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
            ->logOnly(['user_id', 'permohonan_id', 'pesan', 'rating'])
            ->setDescriptionForEvent(fn(string $eventName) => "User memberikan feedback dengan status: {$eventName}")
            ->useLogName('feedback');
    }
    
}
