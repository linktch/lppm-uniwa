<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('indikator_hafalan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_indikator', 255);
            $table->boolean('laki_laki')->default(true);
            $table->boolean('perempuan')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Insert data awal (11 indikator dari tabel yang Anda berikan)
        $this->insertInitialData();
    }

    private function insertInitialData()
    {
        $indikators = [
            ['nama_indikator' => 'Sholawat Wahidiyah', 'laki_laki' => true, 'perempuan' => true, 'urutan' => 1],
            ['nama_indikator' => 'Tahlil ala Wahidiyah', 'laki_laki' => true, 'perempuan' => true, 'urutan' => 2],
            ['nama_indikator' => 'Bacaan Qunut', 'laki_laki' => true, 'perempuan' => true, 'urutan' => 3],
            ['nama_indikator' => 'Wirid Ba\'da Maghrib', 'laki_laki' => true, 'perempuan' => true, 'urutan' => 4],
            ['nama_indikator' => 'Do\'a Kecerdasan', 'laki_laki' => true, 'perempuan' => true, 'urutan' => 5],
            ['nama_indikator' => 'Do\'a Laduni', 'laki_laki' => true, 'perempuan' => true, 'urutan' => 6],
            ['nama_indikator' => 'Do\'a Kesehatan', 'laki_laki' => true, 'perempuan' => true, 'urutan' => 7],
            ['nama_indikator' => 'Do\'a Penyiaran', 'laki_laki' => true, 'perempuan' => true, 'urutan' => 8],
            ['nama_indikator' => 'Adzan dan Iqomah', 'laki_laki' => true, 'perempuan' => false, 'urutan' => 9],
            ['nama_indikator' => 'Imam Sholat Jenazah', 'laki_laki' => true, 'perempuan' => false, 'urutan' => 10],
            ['nama_indikator' => 'Imam Sholat Maktumah', 'laki_laki' => true, 'perempuan' => false, 'urutan' => 11],
        ];

        foreach ($indikators as $indikator) {
            DB::table('indikator_hafalan')->insert($indikator);
        }
    }

    public function down()
    {
        Schema::dropIfExists('indikator_hafalan');
    }
};