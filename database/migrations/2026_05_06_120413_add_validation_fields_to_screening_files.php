<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('screening_files', function (Blueprint $table) {
            // status per file
            $table->enum('status_surat', ['pending', 'valid', 'ditolak'])->default('pending');
            $table->enum('status_ktp', ['pending', 'valid', 'ditolak'])->default('pending');
            $table->enum('status_spp', ['pending', 'valid', 'ditolak'])->default('pending');

            // keterangan per file
            $table->text('keterangan_surat')->nullable();
            $table->text('keterangan_ktp')->nullable();
            $table->text('keterangan_spp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('screening_files', function (Blueprint $table) {
            //
        });
    }
};
