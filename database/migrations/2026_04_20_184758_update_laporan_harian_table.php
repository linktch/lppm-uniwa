<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('laporan_harian', function (Blueprint $table) {
            $table->foreignId('periode_id')
                ->nullable()
                ->constrained('periode')
                ->cascadeOnDelete();

            $table->foreignId('kegiatan_id')
                ->nullable()
                ->constrained('kegiatan')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('laporan_harian', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropForeign(['kegiatan_id']);

            $table->dropColumn(['periode_id', 'kegiatan_id']);
        });
    }
};