<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'nama' => 'admin',
                'username' => 'admin',
                'email' => 'admin@mail.com',
                'no_telepon' => '1111111',
                'role' => 'Admin',
                'password' => bcrypt('Admin123$'),
            ],
        ];

        DB::table('users')->insert($data);
    }
}
