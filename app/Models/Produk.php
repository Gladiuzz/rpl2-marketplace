<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'id_kategori','nama','jumlah','harga','gambar','deskripsi','id_penjual','deskripsi'
    ];

    public function kategoriProduk()
    {
        return $this->belongsTo(KategoriProduk::class, 'id_kategori', 'id');
    }

    public function pesananProduk()
    {
        return $this->hasMany(PesananProduk::class, 'id_produk', 'id');
    }

    public function penjual()
    {
        return $this->belongsTo(Penjual::class, 'id_penjual', 'id');
    }
}
