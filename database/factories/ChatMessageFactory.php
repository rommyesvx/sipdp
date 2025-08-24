<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatMessageFactory extends Factory
{
    protected $model = ChatMessage::class;

    public function definition(): array
    {
        return [
            'chat_room_id' => ChatRoom::factory(),
            'sender_id'    => User::inRandomOrder()->value('id') ?? User::factory(),
            'message'      => $this->faker->sentence(rand(6, 14)),
            'is_system'    => false,
            'is_edited'    => false,
            'reply_to_id'  => null,
            'file_path'    => null,
            'file_name'    => null,
            'file_size'    => null,
            'mime_type'    => null,
            'meta'         => null,
            'sent_at'      => now()->subMinutes(rand(0, 200)),
        ];
    }
}
