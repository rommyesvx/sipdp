<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasOne
};

class ChatMessage extends Model
{
    protected $fillable = ['permohonan_data_id', 'user_id', 'message'];

    public function permohonan()
    {
        return $this->belongsTo(PermohonanData::class, 'permohonan_data_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class, 'permohonan_data_id', 'permohonan_data_id')
            ->latestOfMany();
    }
    public function scopeUnreadForAdmin($query)
    {
        return $query->where('is_read', false)
            ->where('sender_role', '!=', 'admin');
    }
}
