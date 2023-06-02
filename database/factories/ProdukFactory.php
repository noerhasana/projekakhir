<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $colors = [
            'merah',
            'kuning',
            'hijau',
            'biru',
            'putih',
        ];

        return [
            'nama' => $this->faker->words(4, true),
            'deskripsi' => $this->faker->paragraph(),
            'jumlah_terjual' => 3,
            'harga' => rand(20, 40) * 10_000,
            'warna' => $colors[rand(0, 4)],
            'jumlah_stok' => rand(20, 40),
            'ukuran' => json_encode(["S", "M", "L", "XL", "XXL"]),
        ];
    }
}
