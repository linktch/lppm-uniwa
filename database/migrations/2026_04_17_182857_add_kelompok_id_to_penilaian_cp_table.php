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
Schema::table('penilaian_cp', function (Blueprint $table) {
    $table->unsignedBigInteger('kelompok_id')->after('user_id');

    $table->foreign('kelompok_id')
        ->references('id')
        ->on('kelompok') // ⚠️ pastikan ini benar
        ->cascadeOnDelete();
});
}

public function down(): void
{
    Schema::table('penilaian_cp', function (Blueprint $table) {
        $table->dropForeign(['kelompok_id']);
        $table->dropColumn('kelompok_id');
    });
}
};
