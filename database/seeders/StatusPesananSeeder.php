<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusPesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            [
                'kode' => 'BELUM_DIBAYAR',
                'nama' => 'Belum Dibayar',
                'deskripsi' => '',
            ],
            [
                'kode' => 'SUDAH_DIBAYAR',
                'nama' => 'Sudah Dibayar',
                'deskripsi' => '',
            ],
            [
                'kode' => 'PROSES',
                'nama' => 'Proses',
                'deskripsi' => '',
            ],
            [
                'kode' => 'MENUNGGU_PENJEMPUTAN_KURIR',
                'nama' => 'Menunggu penjemputan kurir',
                'deskripsi' => '',
            ],
            [
                'kode' => 'DI_ANTAR',
                'nama' => 'Di Antar',
                'deskripsi' => '',
            ],
            [
                'kode' => 'SELESAI',
                'nama' => 'Selesai',
                'deskripsi' => '',
            ],
        ];

        DB::table('status_pesanans')->insert($status);
    }
}
