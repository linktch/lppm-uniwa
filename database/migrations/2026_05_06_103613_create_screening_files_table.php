<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screening_files', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('periode_id');
            $table->unsignedBigInteger('kegiatan_id');

            // file upload
            $table->string('file_surat')->nullable();
            $table->string('file_ktp')->nullable();
            $table->string('file_spp')->nullable();

            $table->timestamps();

            // optional foreign key (kalau mau strict)
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('periode_id')->references('id')->on('periode')->cascadeOnDelete();
            $table->foreign('kegiatan_id')->references('id')->on('kegiatan')->cascadeOnDelete();

            // biar tidak double upload
            $table->unique(['user_id', 'periode_id', 'kegiatan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screening_files');
    }
};