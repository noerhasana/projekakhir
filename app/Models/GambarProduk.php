<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GambarProduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
    ];

    protected $appends = [
        'url',
    ];

    public function getUrlAttribute()
    {
        return  env('APP_URL') .Storage::url($this->path);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
