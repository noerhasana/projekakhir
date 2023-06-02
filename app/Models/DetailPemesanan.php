<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'produk_id',
        'pesan',
        'total_pesanan',
        'ukuran',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function komentar()
    {
        return $this->hasOne(KomentarProduk::class);
    }

    public function rating()
    {
        return $this->hasOne(UserRatingProduk::class);
    }
}
