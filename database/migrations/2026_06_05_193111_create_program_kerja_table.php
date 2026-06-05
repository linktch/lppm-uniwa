<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('program_kerja', function (Blueprint $table) {
            $table->id();
            
            // Kolom biasa (tanpa foreign key constraint)
            $table->unsignedBigInteger('kelompok_id')->nullable();
            $table->unsignedBigInteger('periode_id')->nullable();
            $table->unsignedBigInteger('kegiatan_id')->nullable();
            
            $table->string('nama_program');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi')->nullable();
            $table->date('tanggal_pelaksanaan')->nullable();
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->enum('status', ['rencana', 'proses', 'selesai', 'batal'])->default('rencana');
            $table->string('dokumentasi_path')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('target_peserta')->default(0);
            $table->integer('realisasi_peserta')->default(0);
            $table->text('catatan_pembimbing')->nullable();
            $table->integer('nilai')->nullable();
            
            $table->timestamps();
            
            $table->index(['kelompok_id', 'periode_id', 'kegiatan_id']);
            $table->index('status');
            $table->index('tanggal_pelaksanaan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('program_kerja');
    }
};