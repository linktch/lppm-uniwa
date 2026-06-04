<?php
// database/migrations/2026_06_05_005943_create_sertifikats_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            
            // Ganti foreign key dengan bigInteger biasa dulu
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('periode_id')->unsigned()->nullable();
            $table->bigInteger('kegiatan_id')->unsigned()->nullable();
            
            $table->string('nomor_sertifikat')->unique();
            $table->string('nama_mahasiswa');
            $table->string('nim');
            $table->string('prodi')->nullable();
            $table->string('kelompok')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('predikat')->nullable();
            $table->integer('total_nilai')->default(0);
            $table->integer('persentase')->default(0);
            $table->json('capaian_hafalan')->nullable();
            $table->json('data_penanda_tangan')->nullable();
            $table->string('file_path')->nullable();
            $table->date('tanggal_terbit')->default(now());
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index(['user_id', 'periode_id', 'kegiatan_id']);
            $table->index('nomor_sertifikat');
            $table->index('nim');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sertifikats');
    }
};