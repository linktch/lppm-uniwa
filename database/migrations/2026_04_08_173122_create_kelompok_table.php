<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelompok', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelompok');
            $table->foreignId('periode_id')->constrained('periode')->onDelete('cascade');
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->onDelete('cascade'); // bisa KKN, PKM, PAM
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelompok');
    }
};