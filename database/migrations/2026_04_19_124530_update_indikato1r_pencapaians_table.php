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
        Schema::table('indikator_pencapaian', function (Blueprint $table) {
            

            // 🔥 kelompok_id jadi nullable
            $table->uuid('id_prodi')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('indikator_pencapaian', function (Blueprint $table) {
            
            $table->uuid('id_prodi')->nullable(false)->change();
        });
    }
};
