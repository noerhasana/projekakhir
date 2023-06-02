<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PemesananController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        $pemesanan = $user->pemesanan()
            ->with([
                'listDetailPemesanan.produk.gambar',
                'listDetailPemesanan.rating',
                'statusPesanan'
            ])
            ->get();

        $pemesanan = $pemesanan->map(function($value) {
                $value->total_rating = $value->listDetailPemesanan
                    ->filter(fn($val) => ($val->rating != null))
                    ->count();
                $value->total_pemesanan = $value->listDetailPemesanan->count();
                return $value;
            });

        return Inertia::render('Pelanggan/Pemesanan', [
            'pemesanan' => $pemesanan,
        ]);

        // return redirect(route('cart.index'));
    }

    public function store(Request $request)
    {
        $midtransClientKey = MIDTRANS_CLIENT_KEY;
        $listAlamat = Auth::user()->alamat;

        if ($listAlamat->count() == 0) {
            return to_route('profile.edit')
                ->withFragment('list-alamat')
                ->with('message', toastFailed("Silahkan isi alamat terlebih dahulu sebelum melanjutkan pembayaran"));
        }

        $data = $request->list_produk;

        return Inertia::render('Pelanggan/Bayar', [
            'listAlamat' => $listAlamat,
            'data' => $data,
            'midtransClientKey' => $midtransClientKey,
        ]);
    }

    public function tracking($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $detailPengiriman = json_decode($pemesanan->detail_pengiriman, true);
        $shippingOrderId = $detailPengiriman['order_id'];

        try {
            $client = new Client();

            $response = $client->get(SHIPPER_API_HOST."/order/{$shippingOrderId}", [
                'headers' => [
                    'X-API-Key' => SHIPPER_API_KEY,
                ],
            ]);
            
            $responseBody = json_decode($response->getBody(), true);
            $trackings = collect($responseBody['data']['trackings'])
                ->sortBy(function ($value) {
                        return $value['shipper_status']['code'];
                    })
                ->toArray();
            
            $this->updateStatusFromTracking($pemesanan, $trackings);
            return $trackings;
        } catch (RequestException $th) {
            dd(json_decode($th->getResponse()->getBody()));
            throw $th;
        }
    }

    private function updateStatusFromTracking(Pemesanan $pemesanan, Array $trackings)
    {
        $kodeStatusTerakhir = (int) collect($trackings)->last()['shipper_status']['code'];
        $kodeSampai = [2000, 3000, 2010];

        if (in_array($kodeStatusTerakhir, $kodeSampai)) {
            $pemesanan->kode_status = 'SELESAI';
            $pemesanan->save();
        } else if ($kodeStatusTerakhir > 1001) {
            $pemesanan->kode_status = 'DI_ANTAR';
            $pemesanan->save();
        }
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        $pemesanan->load([
            'listDetailPemesanan.produk.gambar',
            'listDetailPemesanan.rating',
            'listDetailPemesanan.komentar',
            'user',
            'alamatTujuan',
        ]);

        return Inertia::render('Pelanggan/DetailPemesanan', [
            'pemesanan' => $pemesanan,
        ]);
    }
}
