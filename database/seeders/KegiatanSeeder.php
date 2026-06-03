<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kegiatan;

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        $kegiatan = [
            ['nama_kegiatan' => 'KKN'],
            ['nama_kegiatan' => 'PKM'],
            ['nama_kegiatan' => 'PAM'],
        ];

        foreach ($kegiatan as $k) {
            Kegiatan::create($k);
        }
    }
}