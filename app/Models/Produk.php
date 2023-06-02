<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'kategori_produk_id',
        'jumlah_terjual',
        'parent_produk_id',
        'harga',
        'warna',
        'jumlah_stok',
        'ukuran',
        'panjang',
        'berat',
        'tinggi',
        'lebar',
    ];

    protected $appends = [
        'ringkasanRating',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_produk_id');
    }

    public function gambar()
    {
        return $this->hasMany(GambarProduk::class);
    }

    public function relateListProduk()
    {
        return $this->hasMany(Produk::class, 'parent_produk_id');
    }

    public function ratings()
    {
        return $this->hasMany(RatingProduk::class);
    }

    public function listKomentar()
    {
        return $this->hasMany(KomentarProduk::class);
    }

    public function pemesanan()
    {
        return $this->hasMany(DetailPemesanan::class);
    }

    public function komentarPemesanan()
    {
        return $this->pemesanan()
            ->has('komentar')
            ->select(['id', 'produk_id'])
            ->with([
                'komentar:detail_pemesanan_id,komentar,user_id',
                'komentar.user:id,name',
                'rating:detail_pemesanan_id,rating'
            ])
            ->orderBy('created_at', 'desc');
    }

    public function getRingkasanRatingAttribute()
    {
        $totalRating = $this->ratings
                ->map(fn($val) => $val->rating * $val->jumlah)
                ->sum();

        $totalGiver = $this->ratings
                ->map(fn($val) => $val->jumlah)
                ->sum();

        $ratings = $this->ratings
            ->mapWithKeys(fn($val) => [ $val->rating => $val->jumlah]);

        
        $avg = ( $totalGiver > 0) ? ($totalRating / $totalGiver) : $totalRating;
        
        return collect([
            'avg' => $avg,
            'detail' => $ratings,
        ]);
    }
}
