<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Str;

class PermohonanData extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'data_permohonan';

    protected $fillable = [
        'user_id',
        'tujuan',
        'asal',
        'tipe',
        'jenis_data',
        'kolom_diminta',
        'file_permohonan',
        'catatan',
        'status',
        'file_hasil',
        'alasan_penolakan',
        'alasan_eskalasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chatMessages()
{
    return $this->hasMany(ChatMessage::class, 'permohonan_data_id');
}

public function lastMessage()
{
    return $this->hasOne(ChatMessage::class, 'permohonan_data_id')
        ->latestOfMany();
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
    public function getFormattedJenisDataAttribute(): string
    {
        // Cek jika bukan JSON, kembalikan apa adanya (untuk kriteria khusus)
        if (!\Illuminate\Support\Str::isJson($this->attributes['jenis_data'])) {
            return nl2br(e($this->attributes['jenis_data']));
        }

        $data = json_decode($this->attributes['jenis_data'], true);

        if (empty($data) || !is_array($data)) {
            return '-';
        }

        // Buat HTML yang berisi serangkaian badge
        $html = '<div class="d-flex flex-wrap gap-1">'; // Wrapper agar badge bisa pindah baris jika tidak cukup
        foreach ($data as $item) {
            // Pastikan kriteria dan nilai ada sebelum ditampilkan
            if (isset($item['kriteria']) && isset($item['nilai'])) {
                $html .= '<span class="badge text-dark-emphasis bg-light-subtle border">';
                $html .= e($item['kriteria']) . ': <strong class="fw-semibold">' . e($item['nilai']) . '</strong>';
                $html .= '</span>';
            }
        }
        $html .= '</div>';

        return $html;
    }
    public function getJenisDataRingkasAttribute(): string
    {
        // Cek jika bukan JSON, kembalikan apa adanya (untuk kriteria khusus)
        if (!Str::isJson($this->attributes['jenis_data'])) {
            return Str::limit($this->attributes['jenis_data'], 50); // Batasi panjang teks
        }

        $data = json_decode($this->attributes['jenis_data'], true);

        if (empty($data) || !is_array($data)) {
            return '-';
        }

        $kriteriaNames = array_column($data, 'kriteria');

        return Str::limit(implode(', ', $kriteriaNames), 50);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($permohonan) {

            $prefix = 'REQ';

            $yearMonth = now()->format('Ym');

            $latestPermohonan = self::where('nomor_permohonan', 'LIKE', "{$prefix}-{$yearMonth}-%")->latest('id')->first();

            $nextNumber = 1;
            if ($latestPermohonan) {
                $lastNumber = (int) substr($latestPermohonan->nomor_permohonan, -3);
                $nextNumber = $lastNumber + 1;
            }

            $permohonan->nomor_permohonan = "{$prefix}-{$yearMonth}-" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }
    public function files()
    {
        return $this->hasMany(FilePermohonan::class, 'permohonan_data_id');
    }

    // Helper relationship agar lebih mudah dipanggil
    public function suratPengantar()
    {
        return $this->hasOne(FilePermohonan::class, 'permohonan_data_id')->where('tipe_file', 'pengantar');
    }

    public function fileHasil()
    {
        return $this->hasOne(FilePermohonan::class, 'permohonan_data_id')->where('tipe_file', 'hasil');
    }
}
