<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualSeeder extends Seeder
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
                'id_user' => 102,
                'nama_toko' => 'Toko User 2',
                'alamat_toko' => 'Bandung',
            ],
            [
                'id' => 102,
                'id_user' => 103,
                'nama_toko' => 'Toko User 3',
                'alamat_toko' => 'Bogor',
            ],
        ];

        DB::table('penjual')->insert($data);
    }
}
