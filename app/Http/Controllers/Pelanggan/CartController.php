<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pelanggan\StoreCartRequest;
use App\Http\Requests\Pelanggan\UpdateCartRequest;
use App\Models\AlamatPelanggan;
use App\Models\Cart;
use App\Models\Produk;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart()->with(['produk.gambar'])->get();

        $cart->each(function($val) {
            if ($val->total_pesanan > $val->produk->jumlah_stok) {
                $val->total_pesanan = $val->produk->jumlah_stok;
                $val->save();
            }

            if ($val->total_pesanan == 0) {
                $val->delete();
            }
        });
        
        return Inertia::render('Pelanggan/Keranjang', [
            'data' => $cart,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(StoreCartRequest $request)
    {
        $user = Auth::user();
        $cart = $user->cart()->where([
                'produk_id' => $request->produk_id,
                'ukuran' => $request->ukuran,
            ])
            ->first();

        if ($cart) {
            $cart->total_pesanan += $request->total_pesanan;
            $cart->save();
        } else {
            $user->cart()->create($request->validated());
        }

        return back()->with('message', toastSuccess('Berhasil menambahkan ke keranjang'));
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(UpdateCartRequest $request, $id)
    {
        $input = $request->validated();

        $alamatPelanggan = Cart::findOrFail($id);
        $alamatPelanggan->update($input);

        return back()->with('message', toastSuccess('Berhasil mengubah jumlah pemesanan'));
    }

    public function destroy($id)
    {
        $cart=Cart::find($id);
        
        if ($cart) {
            $cart->delete();
        }

        return back()->with('message', toastSuccess('Berhasil menghapus'));
    }
}
