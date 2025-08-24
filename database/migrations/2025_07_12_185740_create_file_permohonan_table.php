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
        Schema::create('file_permohonan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_data_id')->constrained('data_permohonan')->onDelete('cascade');
            $table->enum('tipe_file', ['pengantar', 'hasil']);
            $table->string('path');
            $table->string('nama_asli_file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_permohonan');
    }
};
