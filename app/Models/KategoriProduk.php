<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function listProduk()
    {
        return $this->hasMany(Produk::class);
    }
}
