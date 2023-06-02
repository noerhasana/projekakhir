<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\StatusPesanan;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->size ?? 50;

        $pemesanan = Pemesanan::whereNotIn('kode_status', [
                'BELUM_DIBAYAR',
                'SELESAI',
            ])
            ->with([
                'listDetailPemesanan.produk.gambar',
            ]);
        
        $pemesanan = $pemesanan->paginate($size);

        return Inertia::render('Admin/Pemesanan/Index', [
            'pemesanan' => $pemesanan,
        ]);
    }

    public function show(Pemesanan $pemesanan)
    {
        $pemesanan->load([
            'listDetailPemesanan.produk.gambar',
            'user',
            'alamatTujuan',
        ]);

        $statusPesanan =  StatusPesanan::whereIn('kode', [
            'MENUNGGU_PENJEMPUTAN_KURIR',
            'PROSES',
        ])->get(['kode', 'nama']);

        return Inertia::render('Admin/Pemesanan/Show', [
            'pemesanan' => $pemesanan,
            'statusPesanan' => $statusPesanan,
        ]);
    }

    public function update(Request $request, Pemesanan $pemesanan)
    {
        $input = $request->validate([
            'kode_status' => 'required',
        ]);

        $pemesanan->update($input);

        if ($pemesanan->kode_status == 'MENUNGGU_PENJEMPUTAN_KURIR') {
            $this->requestPickup($pemesanan);
        }

        return response()->json($pemesanan->toArray());
    }

    public function requestPickup(Pemesanan $pemesanan)
    {
        $detailPengiriman = json_decode($pemesanan->detail_pengiriman, true);
        $pickupTimeslot = $this->getPickupTimeslot();


        $requestPickup = [
            'data' => [
                'order_activation' => [
                    'order_id' => [
                        $detailPengiriman['order_id']
                    ],
                    'timezone' => 'Asia/Jakarta',
                    'start_time'=> "{$pickupTimeslot['start_time']}",
                    'end_time'=> "{$pickupTimeslot['end_time']}"
                ]
            ]
        ];

        try {
            $client = new Client();

            $response = $client->post(SHIPPER_API_HOST."/pickup/timeslot", [
                'headers' => [
                    'X-API-Key' => SHIPPER_API_KEY,
                ],
                'json' => $requestPickup,
            ]);
            
            $responseBody = json_decode($response->getBody(), true);
            $pickupTime = $responseBody['data']['order_activations'][0]['pickup_time'];
            
            $pemesanan->pickup_time = $pickupTime;
            $pemesanan->save();
            
        } catch (RequestException $th) {
            dd(json_decode($th->getResponse()->getBody()));
            throw $th;
        }
    }

    public function getPickupTimeslot($timeZone = 'Asia/Jakarta') : Array
    {
        try {
            $client = new Client();

            $response = $client->get(SHIPPER_API_HOST."/pickup/timeslot?time_zone={$timeZone}", [
                'headers' => [
                    'X-API-Key' => SHIPPER_API_KEY,
                ],
            ]);
            
            $responseBody = json_decode($response->getBody(), true);
            $timeSlots = $responseBody['data']['time_slots'];
            $timeSlotCollection = collect($timeSlots);
            $timeSlot = $timeSlotCollection->last();

            return $timeSlot;
        } catch (RequestException $th) {
            dd(json_decode($th->getResponse()->getBody()));
            throw $th;
        }
    }
}
