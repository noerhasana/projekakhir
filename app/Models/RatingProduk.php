<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingProduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'produk_id',
        'rating',
        'jumlah',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
