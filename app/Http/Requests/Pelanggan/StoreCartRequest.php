<?php

namespace App\Http\Requests\Pelanggan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCartRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->hasRole('pelanggan');
    }

    public function rules()
    {
        return [
            'produk_id' => 'required|exists:produks,id',
            'ukuran' => 'required',
            'total_pesanan' => 'required|integer',
        ];
    }

    public function attributes()
    {
        return [
            'produk_id' => 'produk',
        ];
    }
}
