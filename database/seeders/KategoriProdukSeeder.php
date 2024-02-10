<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriProdukSeeder extends Seeder
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
                'id' => 1,
                'nama' => 'Monitor',
                'deskripsi' => 'salah satu jenis sistem perangkat keras keluaran (Output Device System) sebagai perangkat yang difungsikan untuk mengeluarkan hasil pemrosesan CPU seperti tulisan (huruf, angka, karakter khusus, simbol lain), grafik, gambar/image, suara dan bentuk khusus yang dapat dibaca oleh mesin.',
            ],
        ];

        DB::table('kategori_produk')->insert($data);
    }
}
