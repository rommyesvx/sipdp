<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo
};

class ChatParticipant extends Model
{
    protected $table = 'chat_participants';

    protected $fillable = [
        'chat_room_id',
        'user_id',
        'role',
        'last_read_at',
        'muted',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
        'muted'        => 'boolean',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Tandai semua pesan sebagai telah dibaca hingga timestamp sekarang.
     */
    public function markRead(): void
    {
        $this->last_read_at = now();
        $this->save();
    }

    /**
     * Menghitung jumlah pesan belum dibaca oleh peserta ini.
     */
    public function unreadCount(): int
    {
        $lastRead = $this->last_read_at;

        return $this->room
            ->messages()
            ->when($lastRead, fn ($q) => $q->where('sent_at', '>', $lastRead))
            ->count();
    }

    /**
     * Apakah user ini merupakan admin di room (staf_it/kepala_bidang/admin)?
     * Sifatnya fleksibel—silakan sesuaikan kebutuhan role di SIPDP.
     */
    public function isAdminLike(): bool
    {
        return in_array($this->role, ['admin', 'staf_it', 'kepala_bidang'], true);
    }
}
