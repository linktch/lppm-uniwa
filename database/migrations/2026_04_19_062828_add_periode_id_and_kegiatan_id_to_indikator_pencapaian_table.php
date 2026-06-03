<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('indikator_pencapaian', function (Blueprint $table) {

            $table->foreignId('periode_id')
                ->after('id')
                ->nullable()
                ->constrained('periode')
                ->nullOnDelete();

            $table->foreignId('kegiatan_id')
                ->after('periode_id')
                ->nullable()
                ->constrained('kegiatan')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('indikator_pencapaian', function (Blueprint $table) {

            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');

            $table->dropForeign(['kegiatan_id']);
            $table->dropColumn('kegiatan_id');
        });
    }
};