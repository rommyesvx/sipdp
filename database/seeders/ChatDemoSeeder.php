<?php

namespace Database\Seeders;

use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatDemoSeeder extends Seeder
{
    /**
     * Seeder untuk membuat 1 room yang mengikat satu permohonan (jika ada),
     * dengan peserta: pemohon & staf IT, lalu beberapa pesan dummy.
     * Sesuaikan nama model/tabel DataPermohonan dengan milikmu.
     */
    public function run(): void
    {
        // Ambil contoh user pemohon / staf_it / kepala_bidang dari sistemmu.
        // Jika belum ada role management, ambil user acak sebagai contoh.
        $pemohon   = User::first();
        $stafIt    = User::skip(1)->first() ?? $pemohon;

        // Cari permohonan terbaru jika ada (opsional)
        $permohonanId = null;
        
            $permohonanId = DB::table('data_permohonan')->latest('id')->value('id');
        

        // Buat room
        $room = ChatRoom::create([
            'permohonan_data_id' => $permohonanId,
            'subject'            => $permohonanId
                ? "Diskusi Permohonan #$permohonanId"
                : "Diskusi Umum",
            'created_by'         => $pemohon?->id,
            'last_message_at'    => now(),
        ]);

        // Tambah peserta
        DB::table('chat_participants')->insert([
            [
                'chat_room_id' => $room->id,
                'user_id'      => $pemohon?->id,
                'role'         => 'pemohon',
                'last_read_at' => now(),
                'muted'        => false,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'chat_room_id' => $room->id,
                'user_id'      => $stafIt?->id,
                'role'         => 'staf_it',
                'last_read_at' => null,
                'muted'        => false,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);

        // Tambah beberapa pesan
        ChatMessage::create([
            'chat_room_id' => $room->id,
            'sender_id'    => $pemohon?->id,
            'message'      => 'Selamat pagi, saya ingin menanyakan progres permohonan data pegawai.',
            'sent_at'      => now()->subMinutes(10),
        ]);

        ChatMessage::create([
            'chat_room_id' => $room->id,
            'sender_id'    => $stafIt?->id,
            'message'      => 'Selamat pagi, permohonan Anda sedang diproses. Perkiraan selesai hari ini.',
            'sent_at'      => now()->subMinutes(8),
        ]);

        ChatMessage::create([
            'chat_room_id' => $room->id,
            'sender_id'    => $pemohon?->id,
            'message'      => 'Baik, terima kasih informasinya.',
            'sent_at'      => now()->subMinutes(5),
        ]);

        $room->touchLastMessageAt(now());
    }
}
