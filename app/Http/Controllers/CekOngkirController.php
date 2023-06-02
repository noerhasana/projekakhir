<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class CekOngkirController extends Controller
{
    public function index(Request $request)
    {
        $input = $request->validate([
            'desa_id' => 'required',
            'list_barang' => 'required'
        ]);

        $dimensi = $this->calculateDimension($input['list_barang']);
        $totalHargaBarang = collect($input['list_barang'])->map(fn($val) => $val['total_pesanan'] * $val['produk']['harga'])->sum();
        
        $requestOngkir = [
            'origin' => [
                'area_id' => (int) SHIPPER_API_ORIGIN_AREA_ID,
            ],
            'destination' => [
                "area_id" => (int) $request->desa_id,
            ],
            "for_order" => true,
            "height" => $dimensi['avgSide'],
            "length" => $dimensi['avgSide'],
            "width" => $dimensi['avgSide'],
            "weight" => $dimensi['totalBerat'],
            "item_value" => $totalHargaBarang,
            'cod' => false,
        ];

        try {
            // dd($requestOngkir);
            $client = new Client();

            $response = $client->post(SHIPPER_API_HOST."/pricing/domestic?limit=1000", [
                'headers' => [
                    'X-API-Key' => SHIPPER_API_KEY,
                ],
                'json' => $requestOngkir,
            ]);
            
            $responseBody = json_decode($response->getBody(), true);
            $listPaket = $responseBody['data']['pricings'];
            
            return response()->json($listPaket);
        } catch (RequestException $th) {
            // dd(json_decode($th->getResponse()->getBody()));
            throw $th;
        }
    }

    private function calculateDimension(array $listBarang)
    {
        $collectBarang = collect($listBarang);

        $totalVolume = $collectBarang->map(fn($val) => $val['total_pesanan'] * 
                $val['produk']['lebar'] * 
                $val['produk']['panjang'] * 
                $val['produk']['tinggi'])
            ->sum();

        $avgSide = pow($totalVolume, 1/3);

        $totalBerat = $collectBarang->map(fn($val) => 
                $val['total_pesanan'] * $val['produk']['berat'])
            ->sum();
        
        return [
            'totalVolume' => $totalVolume,
            'totalBerat' => $totalBerat,
            'avgSide' => $avgSide,
        ];
    }
}
