<?php

namespace App\Http\Requests\Pelanggan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCartRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->hasRole('pelanggan');
    }

    public function rules()
    {
        return [
            'total_pesanan' => 'required|integer',
        ];
    }
}
