<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdukRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama' => 'required',
            'kategori_produk_id' => 'required|exists:kategori_produks,id',
            'deskripsi' => 'required',
            'listProduk' => 'required',
            'listProduk.*.harga' => 'required|numeric',
            'listProduk.*.warna' => 'required',
            'listProduk.*.jumlah_stok' => 'required|numeric|min:0',
            'listProduk.*.ukuran' => 'required',
            'listProduk.*.pathImages' => 'required',
            'listProduk.*.panjang' => 'required',
            'listProduk.*.lebar' => 'required',
            'listProduk.*.tinggi' => 'required',
            'listProduk.*.berat' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'listProduk.*.harga' => 'harga',
            'listProduk.*.warna' => 'warna',
            'listProduk.*.jumlah_stok' => 'jumlah stok',
            'listProduk.*.ukuran' => 'ukuran',
            'listProduk.*.pathImages' => 'gambar',
            'listProduk.*.panjang' => 'panjang',
            'listProduk.*.lebar' => 'lebar',
            'listProduk.*.tinggi' => 'tinggi',
            'listProduk.*.berat' => 'berat',
        ];
    }
}
