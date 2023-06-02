<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRatingProduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'produk_id',
        'detail_pemesanan_id',
        'user_id',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPemesanan()
    {
        return $this->belongsTo(DetailPemesanan::class);
    }
}
