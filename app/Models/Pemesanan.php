<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pilihan_paket_pengiriman',
        'alamat_pelanggan_id',
        'jumlah_harga_barang',
        'jumlah_ongkir',
        'kode_status',
        'pay_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listDetailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class);
    }

    public function statusPesanan()
    {
        return $this->belongsTo(StatusPesanan::class, 'kode_status', 'kode');
    }

    public function alamatTujuan()
    {
        return $this->belongsTo(AlamatPelanggan::class, 'alamat_pelanggan_id');
    }
}
