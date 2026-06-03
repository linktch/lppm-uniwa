<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('periode', function (Blueprint $table) {
            $table->string('status')->nullable()->after('tanggal_selesai');
        });
    }

    public function down(): void
    {
        Schema::table('periode', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};