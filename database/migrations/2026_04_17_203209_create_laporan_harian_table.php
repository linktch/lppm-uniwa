<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_harian', function (Blueprint $table) {
            $table->id();

            // Mahasiswa
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Kelompok
            $table->foreignId('kelompok_id')
                ->constrained('kelompok') // 🔥 FIX INI (biasanya plural)
                ->cascadeOnDelete();

            // Tanggal laporan
            $table->datetime('tanggal');

            // Isi laporan
            $table->text('aktivitas');

            $table->string('foto')->nullable();

            // Status laporan
            $table->enum('status', [
                'draft',
                'submitted',
                'revisi',
                'approved'
            ])->default('draft');

            $table->timestamps();

            $table->index(['user_id', 'tanggal']);
            $table->index(['kelompok_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_harian');
    }
};