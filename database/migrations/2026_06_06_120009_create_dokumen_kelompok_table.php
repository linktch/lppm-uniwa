<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dokumen_kelompok', function (Blueprint $table) {
            $table->id();
            
            // Relasi
            $table->unsignedBigInteger('kelompok_id');
            $table->unsignedBigInteger('periode_id');
            $table->unsignedBigInteger('kegiatan_id');
            $table->unsignedBigInteger('user_id')->nullable(); // Uploader
            
            // Jenis dokumen (string, lebih fleksibel)
            $table->string('jenis')->default('program_kerja');
            // Bisa diisi: 'program_kerja', 'laporan_akhir', 'artikel', 'berkas_pendukung', dll
            
            // File
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('file_size')->nullable();
            $table->string('file_type')->nullable();
            
            // Judul
            $table->string('judul')->nullable();
            
            // Konten (untuk artikel)
            $table->text('konten')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'published'])->default('published');
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('kelompok_id')->references('id')->on('kelompok')->onDelete('cascade');
            $table->foreign('periode_id')->references('id')->on('periode')->onDelete('cascade');
            $table->foreign('kegiatan_id')->references('id')->on('kegiatan')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            // Index
            $table->index(['kelompok_id', 'jenis']);
            $table->index('periode_id');
            $table->index('kegiatan_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dokumen_kelompok');
    }
};