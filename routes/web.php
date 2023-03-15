<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Models\Produk;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $jumlahproduk=Produk::count();
    return view('welcome',['jumlahproduk' => $jumlahproduk]);
});

// Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
Route::resource('produk', ProdukController::class);