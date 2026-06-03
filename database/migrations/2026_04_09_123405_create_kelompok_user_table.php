<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelompok_user', function (Blueprint $table) {
            $table->id();

            // relasi
            $table->foreignId('kelompok_id')
                  ->constrained('kelompok')
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // role di dalam kelompok
            $table->enum('role', [
                'tim',
                'mahasiswa',
                'kemahasiswaan',
                'prodi'
            ]);

            $table->timestamps();

            // biar tidak double
            $table->unique(['kelompok_id', 'user_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelompok_user');
    }
};