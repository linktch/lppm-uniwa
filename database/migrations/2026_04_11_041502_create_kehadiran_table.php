<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->id();

            // kelompok
            $table->foreignId('kelompok_id')
                ->constrained('kelompok')
                ->cascadeOnDelete();

            // user mahasiswa
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // tanggal presensi
            $table->date('tanggal');

            // status
            $table->enum('status', ['hadir', 'izin', 'alpha'])
                ->default('alpha');

            $table->timestamps();

            // 1 user hanya 1 presensi per hari per kelompok
            $table->unique(['kelompok_id', 'user_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadiran');
    }
};