<?php

namespace App\Http\Controllers;

use App\Models\WilayahKabupaten;
use App\Models\WilayahKecamatan;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WilayahController extends Controller
{
    public function provinsi()
    {
        $client = new Client();

        $response = $client->get(SHIPPER_API_HOST."/location/country/228/provinces?limit=10000", [
            'headers' => [
                'X-API-Key' => SHIPPER_API_KEY,
            ]
        ]);
        
        $responseBody = json_decode($response->getBody(), true);
        $listData = $responseBody['data'];

        $dataCollection = collect($listData);
        $dataCollection = $dataCollection->map(fn($val) => [
            'id' => $val['id'],
            'nama' => $val['name'],
        ]);

        return response()->json($dataCollection->toArray());
    }

    public function kabupaten(Request $request)
    {
        $client = new Client();

        $response = $client->get(SHIPPER_API_HOST."/location/province/{$request->provinsi_id}/cities?limit=10000", [
            'headers' => [
                'X-API-Key' => SHIPPER_API_KEY,
            ]
        ]);
        
        $responseBody = json_decode($response->getBody(), true);
        $listData = $responseBody['data'];

        $dataCollection = collect($listData);
        $dataCollection = $dataCollection->map(fn($val) => [
            'id' => $val['id'],
            'nama' => $val['name'],
        ]);

        return response()->json($dataCollection->toArray());
    }

    public function kecamatan(Request $request)
    {
        $client = new Client();

        $response = $client->get(SHIPPER_API_HOST."/location/city/{$request->kabupaten_id}/suburbs?limit=10000", [
            'headers' => [
                'X-API-Key' => SHIPPER_API_KEY,
            ]
        ]);
        
        $responseBody = json_decode($response->getBody(), true);
        $listData = $responseBody['data'];

        $dataCollection = collect($listData);
        $dataCollection = $dataCollection->map(fn($val) => [
            'id' => $val['id'],
            'nama' => $val['name'],
        ]);

        return response()->json($dataCollection->toArray());
    }

    public function desa(Request $request)
    {
        $client = new Client();

        $response = $client->get(SHIPPER_API_HOST."/location/suburb/{$request->kecamatan_id}/areas?limit=10000", [
            'headers' => [
                'X-API-Key' => SHIPPER_API_KEY,
            ]
        ]);
        
        $responseBody = json_decode($response->getBody(), true);
        $listData = $responseBody['data'];

        $dataCollection = collect($listData);
        $dataCollection = $dataCollection->map(fn($val) => [
            'id' => $val['id'],
            'nama' => $val['name'],
        ]);

        return response()->json($dataCollection->toArray());
    }

}
