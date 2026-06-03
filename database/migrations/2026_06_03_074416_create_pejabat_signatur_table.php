<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pejabat_signatur', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prodi_fakultas_id');
            $table->string('nama', 255);
            $table->string('jabatan', 255);
            $table->string('signatur_path', 500);
            $table->timestamps();

            // Foreign key (opsional, sesuaikan dengan tabel referensi)
            // $table->foreign('prodi_fakultas_id')->references('id')->on('nama_tabel_referensi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pejabat_signatur');
    }
};