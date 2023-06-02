<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Pemesanan;
use App\Models\Produk;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap as MidtransSnap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BayarController extends Controller
{
    public function index()
    {
    }

    public function store(Request $request)
    {
        $pemesanan = null;

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $alamatPelangganId = $request->alamat_pelanggan_id;
            $listProduk = collect($request->list_produk);
            $listStokProduk = $listProduk->map(fn ($val) => [
                'id' => $val['produk']['id'],
                'jumlah_stok' => $val['produk']['jumlah_stok'] - $val['total_pesanan'],
            ]);
            $listIdKeranjang = $listProduk->map(fn ($val) => $val['id'])->toArray();

            $listPemesanan = $listProduk->map(fn ($val) => [
                'produk_id' => $val['produk_id'],
                'total_pesanan' => $val['total_pesanan'],
                'ukuran' => $val['ukuran'],
                'pesan' => '',
            ]);

            $jumlahHargaBarang = $listProduk->map(fn ($val) => ($val['total_pesanan'] * $val['produk']['harga']))->sum();

            $pilihanPaketPengiriman = $request->pilihan_paket_pengiriman;

            $pemesanan = $user->pemesanan()->create([
                'pilihan_paket_pengiriman'  => json_encode($pilihanPaketPengiriman),
                'alamat_pelanggan_id'       => $alamatPelangganId,
                'jumlah_harga_barang'       => $jumlahHargaBarang,
                'jumlah_ongkir'             => $pilihanPaketPengiriman['final_price'],
                'kode_status'               => 'BELUM_DIBAYAR',
            ]);


            $pemesanan->listDetailPemesanan()->createMany($listPemesanan->toArray());

            Cart::whereIn('id', $listIdKeranjang)->delete();

            $listStokProduk->each(function ($val) {
                Produk::find($val['id'])->update(['jumlah_stok' => $val['jumlah_stok']]);
            });

            DB::commit();
            $this->pengirimanRequest($pemesanan);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        $snapToken = $this->paymentRequest($pemesanan);

        return response()->json($snapToken);
    }

    private function paymentRequest(Pemesanan $pemesanan)
    {
        $serverKey = base64_encode(MIDTRANS_SERVER_KEY . ':');
        $params = [
            'transaction_details' => [
                'order_id' => $pemesanan->id,
                'gross_amount' => $pemesanan->jumlah_harga_barang + $pemesanan->jumlah_ongkir,
            ],
            'customer_details' => $pemesanan->user->toArray(),
        ];

        $client = new Client();
        $response = $client->post(MIDTRANS_SNAP_URL, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . $serverKey,
            ],
            'json' => $params,
        ]);

        return json_decode($response->getBody(), true);
    }

    private function pengirimanRequest(Pemesanan $pemesanan)
    {
        $items = $pemesanan->listDetailPemesanan()->with('produk')->get()
            ->map(function ($val)
            {
                return [
                    'name' => $val->produk->nama,
                    'price' => $val->produk->harga,
                    'qty' => $val->total_pesanan,
                ];
            })->toArray();

        $dimensi = $this->calculateDimension($pemesanan);

        $requestPengiriman = [
            "consignee" => [
                "name" => $pemesanan->user->name,
                "phone_number" => $pemesanan->user->no_hp
            ],
            "consigner" => [
                "name" => env('APP_NAME'),
                "phone_number" => OWNER_PHONE,
            ],
            "courier" => [
                "cod" => false,
                "rate_id" => json_decode($pemesanan->pilihan_paket_pengiriman, true)['rate']['id'],
                "use_insurance" => false
            ],
            "coverage" => "domestic",
            "destination" => [
                "address" => $pemesanan->alamatTujuan->alamat,
                "area_id" => (int) $pemesanan->alamatTujuan->desa_id,
            ],
            "external_id" => "{$pemesanan->id}",
            "origin" => [
                "address" => SHIPPER_API_ORIGIN_ADDRESS,
                "area_id" => (int) SHIPPER_API_ORIGIN_AREA_ID,
            ],
            "package" => [
                "items" => $items,
                "package_type" => 2,
                "price" => $pemesanan->jumlah_harga_barang,
                "weight" => $dimensi['totalBerat'],
                "height" => $dimensi['avgSide'],
                "length" => $dimensi['avgSide'],
                "width" => $dimensi['avgSide']
            ],
            "payment_type" => "cash"
        ];

        try {
            $client = new Client();

            $response = $client->post(SHIPPER_API_HOST . "/order", [
                'headers' => [
                    'X-API-Key' => SHIPPER_API_KEY,
                ],
                'json' => $requestPengiriman,
            ]);

            $responseBody = json_decode($response->getBody(), true)['data'];
            $pemesanan->detail_pengiriman = json_encode($responseBody);
            $pemesanan->save();

            return $responseBody;
        } catch (RequestException $th) {
            dd(json_decode($th->getResponse()->getBody()));
            throw $th;
        }
    }

    public function status($pemesananId)
    {
        $serverKey = base64_encode(MIDTRANS_SERVER_KEY . ':');

        $pemesanan = Pemesanan::findOrFail($pemesananId);
        $client = new Client();
        $response = $client->get(MIDTRANS_STATUS_URL . "/{$pemesananId}/status", [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . $serverKey,
            ],
        ]);
        $responseJson = json_decode($response->getBody(), true);

        $statusTransaksi = ['capture', 'settlement'];

        if (in_array($responseJson['transaction_status'], $statusTransaksi)) {
            $pemesanan->kode_status = 'SUDAH_DIBAYAR';
            $pemesanan->save();
        }

        return response()->json($pemesanan->toArray());
    }

    private function calculateDimension(Pemesanan $pemesanan)
    {
        $collectBarang = $pemesanan->listDetailPemesanan;

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
