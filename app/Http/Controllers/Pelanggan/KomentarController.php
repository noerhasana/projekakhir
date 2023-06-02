<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\DetailPemesanan;
use App\Models\KomentarProduk;
use App\Models\RatingProduk;
use App\Models\UserRatingProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KomentarController extends Controller
{
    public function store(Request $request)
    {

        $input = $request->validate([
            'detail_pemesanan_id' => 'required|exists:detail_pemesanans,id',
            'produk_id' => 'required|exists:produks,id',
            'komentar' => 'required',
            'rating' => 'required|integer',
        ]);

        $detailPemesanan = DetailPemesanan::findOrFail($input['detail_pemesanan_id']);

        DB::transaction(function () use ($input, $detailPemesanan) {
            $userId = Auth::id();

            if (! $detailPemesanan->komentar) {
                KomentarProduk::create([
                    'detail_pemesanan_id' => $input['detail_pemesanan_id'],
                    'produk_id' => $input['produk_id'],
                    'user_id' => $userId,
                    'komentar' => $input['komentar'],
                ]);
            }

            if (! $detailPemesanan->rating) {
                UserRatingProduk::create([
                    'rating' => $input['rating'],
                    'produk_id' => $input['produk_id'],
                    'detail_pemesanan_id' => $input['detail_pemesanan_id'],
                    'user_id' => $userId,
                ]);
        
                $ratingProduk = RatingProduk::firstOrCreate([
                    'produk_id' => $input['produk_id'],
                    'rating' => $input['rating'],
                ]);
        
                $ratingProduk->jumlah++;
                $ratingProduk->save();
            }
    
        });

        $detailPemesanan->load(['komentar', 'rating']);

        return response()->json($detailPemesanan, 201);
    }
}
