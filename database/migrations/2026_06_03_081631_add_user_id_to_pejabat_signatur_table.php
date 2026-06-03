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
        Schema::table('pejabat_signatur', function (Blueprint $table) {
            // Menambahkan kolom user_id setelah id atau setelah kolom tertentu
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            
            // Menambahkan foreign key constraint (opsional, jika ada tabel users)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pejabat_signatur', function (Blueprint $table) {
            // Drop foreign key terlebih dahulu
            $table->dropForeign(['user_id']);
            // Drop kolom user_id
            $table->dropColumn('user_id');
        });
    }
};