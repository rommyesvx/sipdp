<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('chat_room_id')->constrained('chat_rooms')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role', 50)->nullable();

            // Fitur read/unread message
            $table->timestamp('last_read_at')->nullable();

            // Preferensi notifikasi
            $table->boolean('muted')->default(false);

            $table->timestamps();

            // Satu user tidak boleh join room yang sama dua kali
            $table->unique(['chat_room_id', 'user_id']);
            $table->index(['user_id', 'last_read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_participants');
    }
};
