<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penilaian_hafalan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('indikator_id')->constrained('indikator_hafalan')->onDelete('cascade');
            $table->enum('nilai', ['sangat_lancar', 'cukup_lancar'])->nullable()->comment('sangat_lancar, cukup_lancar');
            $table->foreignId('periode_id')->constrained('periode');
            $table->foreignId('kegiatan_id')->constrained('kegiatan');
            $table->foreignId('penilai_id')->nullable()->constrained('users');
            $table->datetime('tanggal_penilaian')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Unique constraint untuk menghindari duplikasi
            $table->unique(['user_id', 'indikator_id', 'periode_id', 'kegiatan_id'], 'unique_penilaian_hafalan');
            
            // Index untuk optimasi query
            $table->index(['periode_id', 'kegiatan_id']);
            $table->index(['user_id', 'periode_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('penilaian_hafalan');
    }
};