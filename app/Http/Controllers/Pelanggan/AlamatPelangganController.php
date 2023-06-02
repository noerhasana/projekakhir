<?php

namespace App\Http\Controllers\Pelanggan;

use App\Models\AlamatPelanggan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AlamatPelangganController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'provinsi' => 'required',
            'provinsi.*' => 'required',
            'kabupaten' => 'required',
            'kabupaten.*' => 'required',
            'kecamatan' => 'required',
            'kecamatan.*' => 'required',
            'desa' => 'required',
            'desa.*' => 'required',
        ]);

        Auth::user()->alamat()->create([
            'nama' => $input['nama'],
            'alamat' => $input['alamat'],
            'provinsi_id' => $input['provinsi']['id'],
            'provinsi' => $input['provinsi']['nama'],
            'kabupaten_id' => $input['kabupaten']['id'],
            'kabupaten' => $input['kabupaten']['nama'],
            'kecamatan_id' => $input['kecamatan']['id'],
            'kecamatan' => $input['kecamatan']['nama'],
            'desa_id' => $input['desa']['id'],
            'desa' => $input['desa']['nama'],
        ]);

        return back()->with('message', toastSuccess('Alamat berhasil ditambah'));
    }

    public function update(Request $request, $id)
    {
        $input = $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'provinsi' => 'required',
            'provinsi.*' => 'required',
            'kabupaten' => 'required',
            'kabupaten.*' => 'required',
            'kecamatan' => 'required',
            'kecamatan.*' => 'required',
            'desa' => 'required',
            'desa.*' => 'required',
        ]);

        $alamatPelanggan = AlamatPelanggan::find($id);
        $alamatPelanggan->update([
            'nama' => $input['nama'],
            'alamat' => $input['alamat'],
            'provinsi_id' => $input['provinsi']['id'],
            'provinsi' => $input['provinsi']['nama'],
            'kabupaten_id' => $input['kabupaten']['id'],
            'kabupaten' => $input['kabupaten']['nama'],
            'kecamatan_id' => $input['kecamatan']['id'],
            'kecamatan' => $input['kecamatan']['nama'],
            'desa_id' => $input['desa']['id'],
            'desa' => $input['desa']['nama'],
        ]);

        return back()->with('message', toastSuccess('Alamat berhasil diubah'));
    }

    public function destroy($id)
    {
        $alamatPelanggan = AlamatPelanggan::find($id);
        $alamatPelanggan->delete();


        return back()->with('message', toastSuccess('Alamat berhasil dihapus'));
    }
}
