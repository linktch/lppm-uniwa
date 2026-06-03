<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_cp', function (Blueprint $table) {
            $table->id();

            // ✅ FIX DI SINI
            $table->foreignId('indikator_pencapaian_id')
                ->constrained('indikator_pencapaian')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('progres', ['tercapai', 'belum_tercapai']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_cp');
    }
};