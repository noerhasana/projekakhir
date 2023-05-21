<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{

    use HasFactory;
    protected $guarded = [];
    protected $table = 'pemesanan';

    public function Produk()
    {
        return $this->belongsTo(Produk::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
