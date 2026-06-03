<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'email' => 'admin@mail.com',
            'password' => Hash::make('//////'), // password dummy
            'role' => 'superadmin',
            'permissions' => json_encode(['all']), // bisa diatur sesuai kebutuhan
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'username' => 'superadmin',
            'phone' => '08123456789',
            'last_login' => now(),
            'status_mahasiswa' => 'AKTIF',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}