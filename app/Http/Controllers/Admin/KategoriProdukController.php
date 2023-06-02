<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreKategoriProdukRequest;
use App\Http\Requests\Admin\UpdateKategoriProdukRequest;
use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class KategoriProdukController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->size ?? 50;

        $kategoriProduk = KategoriProduk::select(['id', 'nama', 'deskripsi']);

        if ($request->search) {
            $kategoriProduk = $kategoriProduk->where('nama', 'like', "%{$request->search}%");
        }

        $kategoriProduk = $kategoriProduk->orderBy('nama')->paginate($size);

        return Inertia::render('Admin/KategoriProduk/Index', [
            'data' => $kategoriProduk,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/KategoriProduk/Create');
    }

    public function store(StoreKategoriProdukRequest $request)
    {
        $kategoriProduk = KategoriProduk::create($request->validated());

        return to_route('kategori-produk.index')->with('message', toastSuccess("Data berhasil ditambah"));
    }

    public function show($id)
    {
        $kategoriProduk =  KategoriProduk::findOrFail($id);

        return Inertia::render('Admin/KategoriProduk/Show', [
            'data' => $kategoriProduk,
        ]);
    }

    public function edit($id)
    {
        $kategoriProduk =  KategoriProduk::findOrFail($id);

        return Inertia::render('Admin/KategoriProduk/Edit', [
            'data' => $kategoriProduk,
        ]);
    }

    public function update(UpdateKategoriProdukRequest $request, $id)
    {
        $kategoriProduk = KategoriProduk::findOrFail($id);
        $kategoriProduk->update($request->validated());

        return to_route('kategori-produk.index')->with('message', toastSuccess("Data berhasil diubah"));
    }

    public function destroy($id)
    {
        $isUsage = (Produk::where('kategori_produk_id', $id)->count() > 0);

        if ($isUsage) {
            return back()->with('message', toastFailed('Tidak dapat dihapus karena masih mempunyai produk'));
        }

        KategoriProduk::where('id', $id)->delete();

        return back()->with('message', toastSuccess('Data berhasil dihapus'));

    }
}
