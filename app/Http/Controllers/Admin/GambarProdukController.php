<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GambarProduk;
use Illuminate\Http\Request;

class GambarProdukController extends Controller
{
    public function destroy($id)
    {
        GambarProduk::findOrFail($id)->delete();

        return back()->with('message', toastInfo("Gambar Berhasil dihapus"));
    }
}
