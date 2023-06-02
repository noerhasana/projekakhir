<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomentarProduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'detail_pemesanan_id',
        'produk_id',
        'user_id',
        'komentar',
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
