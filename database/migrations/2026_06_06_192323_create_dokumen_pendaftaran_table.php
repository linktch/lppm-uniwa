<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dokumen_pendaftaran', function (Blueprint $table) {
            $table->id();
            
            // Relasi
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('periode_id');
            $table->unsignedBigInteger('kegiatan_id');
            
            // Jenis dokumen (dinamis)
            $table->string('jenis_dokumen', 50);
            $table->string('label', 100);
            
            // File
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('file_size')->nullable();
            $table->string('file_type')->nullable();
            
            // Status verifikasi
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            
            // Apakah wajib?
            $table->boolean('is_required')->default(true);
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('periode_id')->references('id')->on('periode')->onDelete('cascade');
            $table->foreign('kegiatan_id')->references('id')->on('kegiatan')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            
            // Biarkan Laravel membuat index otomatis dengan nama default
            // Tidak perlu menambahkan index custom
        });
    }

    public function down()
    {
        Schema::dropIfExists('dokumen_pendaftaran');
    }
};