<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KategoriProduk::factory(5)
            ->hasListProduk(7)
            ->create();
    }
}
