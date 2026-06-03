<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('periode', function (Blueprint $table) {
            $table->string('kode_periode')->nullable();  // or ->unique() or other constraints
        });
    }

    public function down()
    {
        Schema::table('periode', function (Blueprint $table) {
            $table->dropColumn('kode_periode');
        });
    }
};
