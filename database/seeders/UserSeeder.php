<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
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
                'id' => 101,
                'nama' => 'User1',
                'username' => 'user1',
                'email' => 'user1@mail.com',
                'no_telepon' => '0123456',
                'role' => 'User',
                'password' => bcrypt('user1123'),
            ],
            [
                'id' => 102,
                'nama' => 'User2',
                'username' => 'user2',
                'email' => 'user2@mail.com',
                'no_telepon' => '0123457',
                'role' => 'Penjual',
                'password' => bcrypt('user2123'),
            ],
            [
                'id' => 103,
                'nama' => 'User3',
                'username' => 'user3',
                'email' => 'user3@mail.com',
                'no_telepon' => '0223457',
                'role' => 'Penjual',
                'password' => bcrypt('user3123'),
            ],
        ];

        DB::table('users')->insert($data);
    }
}
