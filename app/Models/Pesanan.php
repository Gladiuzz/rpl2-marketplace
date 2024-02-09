<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'id_user','total_harga','tanggal_pesanan','status','invoice_number','alamat'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id');
    }

    public function pesananProduk()
    {
        return $this->hasMany(PesananProduk::class, 'id_pesanan', 'id');
    }

    public function getTanggal()
    {
        return Carbon::parse($this->attributes['tanggal_pesanan'])->translatedFormat('l, d F Y');
    }
}
