<?php

namespace Database\Seeders;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('wilayah_provinsi')->delete();
        try {
            $responseFetch = $this->fetchApiPage();
            DB::table('wilayah_provinsi')->insert($responseFetch['data']);

            while ($responseFetch['pagination']['current_page'] < $responseFetch['pagination']['total_pages']) {
                $nextPage = $responseFetch['pagination']['current_page'] + 1;
                $responseFetch = $this->fetchApiPage();
                DB::table('wilayah_provinsi')->insert($responseFetch['data']);
            }

        } catch (Exception $e) {
            var_dump($e);
            var_dump("ERROR Seeding Provinsi !!");
        }
    }

    public function fetchApiPage($page = 1)
    {
        $client = new Client();
    
            $response = $client->get(env('SHIPPER_API_HOST').'/location/country/228/provinces', [
                'headers' => [
                    'X-API-Key' => env('SHIPPER_API_KEY'),
                ]
            ]);
            
            $responseBody = json_decode($response->getBody(), true);
            $listData = $responseBody['data'];
            $pagination = $responseBody['pagination'];

            $dataCollection = collect($listData);
            $dataCollection = $dataCollection->map(fn($val) => [
                'id' => $val['id'],
                'nama' => $val['name'],
            ]);

            return [
                'pagination' => $pagination,
                'data' => $dataCollection->toArray(),
            ];
    }
}
