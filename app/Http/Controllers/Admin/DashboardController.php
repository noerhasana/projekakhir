<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $total['produk'] = Produk::count();
        $total['stok'] =  Produk::get('jumlah_stok')->pluck('jumlah_stok')->sum();
        $total['pemesanan'] = Pemesanan::count();
        $total['user'] = User::count();

        return Inertia::render('Admin/Dashboard', [
            'total' => $total,
        ]);
    }
}
