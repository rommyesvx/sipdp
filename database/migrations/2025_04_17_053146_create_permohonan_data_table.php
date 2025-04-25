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
        Schema::create('data_permohonan', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('user_id'); // FK ke tabel users
            $table->string('tujuan');
            $table->string('tipe');
            $table->string('jenis_data');
            $table->string('file_permohonan')->nullable(); // menyimpan path file
            $table->text('catatan')->nullable();
            $table->timestamps();
    
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_data');
    }
};
