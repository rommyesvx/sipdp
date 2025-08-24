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
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('permohonan_data_id')->nullable()->index();
            $table->string('subject')->nullable();                 // Contoh: "Diskusi Permohonan #DP-2025-0001"
            $table->boolean('is_closed')->default(false);          // Jika layanan selesai / room ditutup
            $table->timestamp('last_message_at')->nullable();      // Optimisasi: menampilkan daftar room by recent activity

            // Tracking pencipta room (opsional)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->foreign('permohonan_data_id')
                ->references('id')
                ->on('data_permohonan')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
