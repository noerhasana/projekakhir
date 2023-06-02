<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPesanan extends Model
{
    use HasFactory;

    public function listPemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'kode_status', 'status');
    }
}
