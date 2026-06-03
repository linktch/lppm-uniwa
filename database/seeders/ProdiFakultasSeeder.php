<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiFakultasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'id_fakultas' => 1,
                'id_prodi' => 'e28c160a-194d-4aad-a3fd-95e14b065c86',
                'kode_program_studi' => '62201',
                'nama_program_studi' => 'S1 Akuntansi',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
            [
                'id_fakultas' => 1,
                'id_prodi' => '7c731e9b-9919-4c02-b29a-be00bf3f9af2',
                'kode_program_studi' => '61201',
                'nama_program_studi' => 'S1 Manajemen',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
            [
                'id_fakultas' => 2,
                'id_prodi' => 'ec1a1d13-6f45-4222-8cd1-d969eaf1e2ca',
                'kode_program_studi' => '15401',
                'nama_program_studi' => 'D3 Kebidanan',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
            [
                'id_fakultas' => 2,
                'id_prodi' => 'e88b408f-ae95-4280-be40-355321ac2ae3',
                'kode_program_studi' => '14401',
                'nama_program_studi' => 'D3 Keperawatan',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
            [
                'id_fakultas' => 3,
                'id_prodi' => 'e1fa9589-c429-4fb6-a296-fe72484d9683',
                'kode_program_studi' => '88203',
                'nama_program_studi' => 'S1 Pendidikan Bahasa Inggris',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
            [
                'id_fakultas' => 3,
                'id_prodi' => '35e211a8-ca54-40d4-ab82-e1f633bd6eeb',
                'kode_program_studi' => '86202',
                'nama_program_studi' => 'S1 Pendidikan Guru Pendidikan Anak Usia Dini',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
            [
                'id_fakultas' => 3,
                'id_prodi' => 'a0732e4d-3a92-4afd-b37c-9d38a41b2bdc',
                'kode_program_studi' => '84204',
                'nama_program_studi' => 'S1 Pendidikan Kimia',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
            [
                'id_fakultas' => 3,
                'id_prodi' => 'd10553ae-0835-4c6c-bd18-84290ab0b991',
                'kode_program_studi' => '84202',
                'nama_program_studi' => 'S1 Pendidikan Matematika',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
            [
                'id_fakultas' => 4,
                'id_prodi' => 'c9c3e3b4-664f-4fe5-bd0c-1df9f7cdfce0',
                'kode_program_studi' => '54201',
                'nama_program_studi' => 'S1 Agribisnis',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
            [
                'id_fakultas' => 4,
                'id_prodi' => '041f8323-e381-480a-b10c-bd0febc9b36b',
                'kode_program_studi' => '54211',
                'nama_program_studi' => 'S1 Agroteknologi',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
            [
                'id_fakultas' => 5,
                'id_prodi' => 'fac120eb-d341-46c8-8981-683b53eb2ea7',
                'kode_program_studi' => '74230',
                'nama_program_studi' => 'S1 Hukum Keluarga Islam (Ahwal Syakhshiyyah)',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
            [
                'id_fakultas' => 6,
                'id_prodi' => '3ddfe4c7-6d92-45b3-80a4-7b67ade91f80',
                'kode_program_studi' => '26201',
                'nama_program_studi' => 'S1 Teknik Industri',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
            [
                'id_fakultas' => 6,
                'id_prodi' => '6ff9d1da-9fae-44b5-93ff-0dc79c69cb74',
                'kode_program_studi' => '55202',
                'nama_program_studi' => 'S1 Teknik Informatika',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
            [
                'id_fakultas' => 6,
                'id_prodi' => '86f616f7-f898-4703-805e-80006ff8d731',
                'kode_program_studi' => '21201',
                'nama_program_studi' => 'S1 Teknik Mesin',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
            [
                'id_fakultas' => 6,
                'id_prodi' => '83289f0c-7e31-4db6-8158-e554af683d5c',
                'kode_program_studi' => '22201',
                'nama_program_studi' => 'S1 Teknik Sipil',
                'created_at' => '2022-06-15 20:32:46',
                'updated_at' => '2022-06-15 20:32:46',
            ],
        ];

        DB::table('prodi_fakultas')->insert($data);
    }
}