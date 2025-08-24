<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Notifications\VerifyEmailNotification;


class User extends Authenticatable implements MustVerifyEmail
{

    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'no_hp',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'no_hp' => 'encrypted',
        ];
    }
    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'permohonan_id');
    }
    public function permohonanData()
    {
        return $this->hasMany(PermohonanData::class);
    }
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }
    public function chatRooms(): BelongsToMany
    {
        return $this->belongsToMany(ChatRoom::class, 'chat_participants')
            ->withPivot(['role', 'last_read_at', 'muted'])
            ->withTimestamps();
    }
    public function chatParticipations(): HasMany
    {
        return $this->hasMany(ChatParticipant::class, 'user_id');
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    /**
     * Helper: ambil semua room terkait permohonan tertentu yang diikuti user ini.
     */
    public function roomsByPermohonan(int $permohonanId)
    {
        return $this->chatRooms()->where('permohonan_data_id', $permohonanId);
    }
}
