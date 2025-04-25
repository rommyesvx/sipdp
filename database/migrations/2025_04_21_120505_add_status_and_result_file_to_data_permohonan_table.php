<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('data_permohonan', function (Blueprint $table) {
            $table->string('status')->default('diajukan'); // status: diajukan, diproses, selesai, ditolak
            $table->string('file_hasil')->nullable(); // path ke file hasil jika status selesai
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_permohonan', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('file_hasil');
        });
    }
};
