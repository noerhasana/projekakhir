<?php

namespace Database\Seeders;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahKabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client();

        $response = $client->get(env('RAJA_ONGKIR_API_HOST').'/city', [
            'headers' => [
                'key' => env('RAJA_ONGKIR_API_KEY'),
            ]
        ]);

        try {
            $responseBody = json_decode($response->getBody(), true);
            $cities = $responseBody['rajaongkir']['results'];
            $cityCollection = collect($cities);
            $cityCollection = $cityCollection->map(fn($val) => [
                'id' => $val['city_id'],
                'nama' => $val['city_name'],
                'provinsi_id' => $val['province_id'],
            ]);
    
            DB::table('wilayah_kabupaten')->delete();
            DB::table('wilayah_kabupaten')->insert($cityCollection->toArray());
        } catch (Exception $e) {
            var_dump($e);
            var_dump("ERROR Seeding Kabupaten !!");
        }
    }
}
