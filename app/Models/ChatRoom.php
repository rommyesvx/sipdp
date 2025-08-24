<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, BelongsToMany, HasMany
};

class ChatRoom extends Model
{
    protected $fillable = [
        'data_permohonan_id',
        'subject',
        'is_closed',
        'last_message_at',
        'created_by',
    ];

    protected $casts = [
        'is_closed'       => 'boolean',
        'last_message_at' => 'datetime',
    ];

    /**
     * Relasi ke entitas permohonan (SIPDP).
     * Pastikan nama model Permohonan kamu benar (mis. App\Models\DataPermohonan).
     * Di sini contoh generik: tabel = data_permohonan, PK = id.
     */
    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(PermohonanData::class, 'permohonan_data_id');
    }

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): BelongsToMany
    {
        // Pivot: chat_participants dengan kolom tambahan role, last_read_at, muted, timestamps
        return $this->belongsToMany(User::class, 'chat_participants')
            ->withPivot(['role', 'last_read_at', 'muted'])
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('sent_at', 'asc');
    }

    /**
     * Scope: Room yang diikuti user tertentu.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->whereHas('participants', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }

    /**
     * Akses cepat: total peserta.
     */
    public function participantsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->participants()->count()
        );
    }

    /**
     * Akses cepat: total pesan.
     */
    public function messagesCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->messages()->count()
        );
    }

    /**
     * Tandai waktu pesan terakhir untuk optimisasi inbox.
     */
    public function touchLastMessageAt(?\DateTimeInterface $when = null): void
    {
        $this->forceFill([
            'last_message_at' => $when ?? now(),
        ])->save();
    }

    /**
     * Cek apakah room tertutup (layanan selesai).
     */
    public function isClosed(): bool
    {
        return (bool) $this->is_closed;
    }

    /**
     * Tutup room dengan alasan opsional.
     * (Pesan sistem akan ditambahkan pada tahap berikutnya—event/message system.)
     */
    public function close(?string $reason = null): void
    {
        $this->is_closed = true;
        $this->save();

        // Placeholder logic untuk mencatat reason akan kita kembangkan di Tahap 2 (event sistem).
        // Mis. $this->messages()->create([... 'is_system' => true, 'message' => "Room ditutup: $reason"]);
    }
}
