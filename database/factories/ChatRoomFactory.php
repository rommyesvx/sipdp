<?php

namespace Database\Factories;

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatRoomFactory extends Factory
{
    protected $model = ChatRoom::class;

    public function definition(): array
    {
        return [
            'permohonan_data_id' => null, // set manual jika ingin kaitkan ke permohonan tertentu
            'subject'            => 'Diskusi Permohonan ' . $this->faker->numerify('DP-####'),
            'is_closed'          => false,
            'last_message_at'    => now()->subMinutes(rand(0, 200)),
            'created_by'         => User::inRandomOrder()->value('id') ?? User::factory(),
        ];
    }
}
