<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_pesanan',
        'produk_id',
        'ukuran',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
