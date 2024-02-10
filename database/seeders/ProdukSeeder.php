<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
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
                'id_kategori' => 1,
                'id_penjual' => 101,
                'nama' => 'Acer CG437K S',
                'jumlah' => '100',
                'harga' => '10000000',
                'gambar' => 'gambar.png',
                'deskripsi' => 'Monitor terbaru dari Acer',
                'status' => 'Non Aktif'
            ],
            [
                'id' => 102,
                'id_kategori' => 1,
                'id_penjual' => 102,
                'nama' => 'Acer CG453',
                'jumlah' => '25',
                'harga' => '2500000',
                'gambar' => 'gambar.png',
                'deskripsi' => 'Monitor terlaris dari Acer',
                'status' => 'Non Aktif'
            ],
        ];

        DB::table('produk')->insert($data);
    }
}
