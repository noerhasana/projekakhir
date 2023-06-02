<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatPelanggan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nama',
        'alamat',
        'provinsi_id',
        'provinsi',
        'kabupaten_id',
        'kabupaten',
        'kecamatan_id',
        'kecamatan',
        'desa_id',
        'desa',
    ];
}
