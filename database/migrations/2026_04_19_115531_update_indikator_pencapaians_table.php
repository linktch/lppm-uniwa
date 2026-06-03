<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('indikator_pencapaian', function (Blueprint $table) {

            // 🔥 ubah id_prodi jadi UUID / string
            $table->uuid('id_prodi')->change();

            // 🔥 kelompok_id jadi nullable
            $table->unsignedBigInteger('kelompok_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('indikator_pencapaian', function (Blueprint $table) {

            // rollback (opsional)
            $table->unsignedBigInteger('id_prodi')->change();
            $table->unsignedBigInteger('kelompok_id')->nullable(false)->change();
        });
    }
};