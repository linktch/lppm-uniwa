<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('timeline_kegiatan', function (Blueprint $table) {
            $table->enum('jenis', ['Pendaftaran', 'Pembekalan', 'Pelaksanaan', 'Pelaporan', 'Evaluasi'])->nullable()->after('status');
            // atau jika ingin string biasa:
            // $table->string('jenis')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('timeline_kegiatan', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
    }
};