<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('indikator_pencapaian', function (Blueprint $table) {
            $table->id();

            // FK manual biar aman
            $table->unsignedBigInteger('kelompok_id');

            $table->string('indikator');
            $table->timestamps();

            // FOREIGN KEY (WAJIB sesuai nama tabel)
            $table->foreign('kelompok_id')
                  ->references('id')
                  ->on('kelompok') // ⬅️ ini penting (bukan kelompoks)
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indikator_pencapaian');
    }
};