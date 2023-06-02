<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->size ?? 50;

        $kategoriProduk = KategoriProduk::all(['id', 'nama']);
        $produk = Produk::select(['id', 'nama', 'harga', 'kategori_produk_id'])
            ->with([
                'gambar',
                'kategori',
            ])
            ->where('parent_produk_id', null);
            

        if ($request->search) {
            $produk = $produk->where('nama', 'like', "%{$request->search}%");
        }

        if ($request->kategori) {
            $produk = $produk->where('kategori_produk_id', $request->kategori);
        }

        $produk = $produk->orderBy('nama')->paginate($size);


        return Inertia::render('Home', [
            'data' => $produk,
            'kategori' => $kategoriProduk,
        ]);
    }

    public function show($id)
    {
        $produk = Produk::select(['id', 'nama', 'kategori_produk_id', 'deskripsi'])
            ->findOrFail($id);

        $listProduk =  Produk::where('id', $id)
            ->orWhere('parent_produk_id', $id)
            ->get([
                'id',
                'harga',
                'warna',
                'jumlah_stok',
                'ukuran',
                'panjang',
                'lebar',
                'tinggi',
                'berat',
            ]);

        $produk->load(['kategori:id,nama']);
        $listProduk->load([
            'gambar:id,produk_id,path',
            'komentarPemesanan' => function ($q) {
                $q->limit(3);
            }
        ]);

        $listGambar = $listProduk
            ->map(function($val) {
                $el = $val['gambar'];
                return $el;
            })
            ->flatten();
        $produk->listProduk = $listProduk->toArray();

        return Inertia::render('ShowProduk', [
            'data' => $produk,
            'listGambar' => $listGambar
        ]);
    }

    public function about()
    {
        return Inertia::render('About');
    }
}
