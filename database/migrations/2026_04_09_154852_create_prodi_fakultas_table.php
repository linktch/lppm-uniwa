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
        Schema::create('prodi_fakultas', function (Blueprint $table) {
            $table->id(); // primary key auto-increment
            $table->char('id_prodi', 36); // UUID, TIDAK sebagai primary key
            $table->unsignedBigInteger('id_fakultas'); // FK fakultas
            $table->string('kode_program_studi', 20);
            $table->string('nama_program_studi', 100);
            $table->timestamps();

            // Index untuk pencarian lebih cepat
            $table->index('id_fakultas');
            $table->unique('id_prodi'); // optional, agar id_prodi unik
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodi_fakultas');
    }
};