<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKategoriProdukRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama' => [
                'required',
                Rule::unique('kategori_produks', 'nama')->ignore($this->route('kategori_produk'))
            ],
            'deskripsi' => '',
        ];
    }
}
