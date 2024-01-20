<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'id_kategori','nama','jumlah','harga'
    ];

    public function kategoriProduk()
    {
        return $this->belongsTo(KategoriProduk::class, 'id_kategori', 'id');
    }
}
