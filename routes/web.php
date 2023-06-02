<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GambarProdukController;
use App\Http\Controllers\Admin\KategoriProdukController;
use App\Http\Controllers\Admin\PemesananController as AdminPemesananController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\CekOngkirController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Pelanggan\AlamatPelangganController;
use App\Http\Controllers\Pelanggan\BayarController;
use App\Http\Controllers\Pelanggan\CartController;
use App\Http\Controllers\Pelanggan\KomentarController;
use App\Http\Controllers\Pelanggan\PemesananController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UploadTempImageController;
use App\Http\Controllers\WilayahController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('detail-produk/{id}', [HomeController::class, 'show'])->name('detail-produk');

Route::group(['middleware' => ['role:admin']], function () {
    Route::post('temp-images', [UploadTempImageController::class, 'store'])->name('temp-images.store');

    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('kategori-produk', KategoriProdukController::class);
    Route::resource('produk', ProdukController::class);
    Route::delete('gambar-produk/{id}', [GambarProdukController::class, 'destroy'])->name('gambar-produk.destroy');
    Route::resource('pemesanan', AdminPemesananController::class);
});

Route::group(['middleware' => ['role:pelanggan']], function () {
    Route::resource('cart', CartController::class);
    Route::resource('order', PemesananController::class);
    Route::get('order/{id}/tracking', [PemesananController::class, 'tracking'])->name('order.tracking');
    Route::post('pay', [BayarController::class, 'store'])->name('pay.store');
    Route::get('pay/{pemesananId}/status', [BayarController::class, 'status'])->name('pay.status');
    Route::post('komentar', [KomentarController::class, 'store'])->name('komentar.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('alamat', AlamatPelangganController::class)
        ->only(['store','update','destroy',]);

    Route::post('avatar', [ProfileController::class, 'avatar'])->name('avatar.store');
});

Route::get('provinsi', [WilayahController::class, 'provinsi']);
Route::get('kabupaten', [WilayahController::class, 'kabupaten']);
Route::get('kecamatan', [WilayahController::class, 'kecamatan']);
Route::get('desa', [WilayahController::class, 'desa']);
Route::post('cek-ongkir', [CekOngkirController::class, 'index'])->name('cek-ongkir');


require __DIR__.'/auth.php';
