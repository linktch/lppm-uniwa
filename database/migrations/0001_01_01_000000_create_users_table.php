<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // id
            $table->string('email')->unique(); // email
            $table->string('password'); // password
            $table->enum('role', ['superadmin', 'mahasiswa'])->default('mahasiswa'); // role
            $table->json('permissions')->nullable(); // permissions
            $table->timestamp('last_login')->nullable(); // last_login
            $table->string('first_name')->nullable(); // first_name
            $table->string('last_name')->nullable(); // last_name
            $table->string('phone')->nullable(); // phone
            $table->string('username')->unique(); // username
            $table->unsignedBigInteger('id_mahasiswa')->nullable(); // id_mahasiswa
            $table->unsignedBigInteger('id_registrasi_mahasiswa')->nullable(); // id_registrasi_mahasiswa
            $table->unsignedBigInteger('id_prodi')->nullable(); // id_prodi
            $table->enum('status_mahasiswa', ['AKTIF','NONAKTIF'])->default('AKTIF'); // status_mahasiswa
            $table->unsignedBigInteger('id_periode')->nullable(); // id_periode
            $table->json('data_mahasiswa')->nullable(); // data_mahasiswa
            $table->string('profile_pic')->nullable(); // profile_pic
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};