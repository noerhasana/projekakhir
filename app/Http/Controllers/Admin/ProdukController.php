<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProdukRequest;
use App\Http\Requests\Admin\UpdateProdukRequest;
use App\Models\KategoriProduk;
use App\Models\Produk;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->size ?? 50;

        $produk = Produk::select(['id', 'nama', 'kategori_produk_id'])
            ->with([
                'gambar',
                'kategori',
            ])
            ->where('parent_produk_id', null);
            

        if ($request->search) {
            $produk = $produk->where('nama', 'like', "%{$request->search}%");
        }

        $produk = $produk->orderBy('nama')->paginate($size);

        return Inertia::render('Admin/Produk/Index', [
            'data' => $produk,
        ]);
    }

    public function create()
    {
        $kategoriProduk = KategoriProduk::all(['id', 'nama']);
        

        return Inertia::render('Admin/Produk/Create', [
            'kategori' => $kategoriProduk,
        ]);
    }

    public function store(StoreProdukRequest $request)
    {
        DB::beginTransaction();
        $parent = null;
        foreach($request->listProduk as $requestProduk) {
            $input = [
                'nama' => $request->nama,
                'kategori_produk_id' => $request->kategori_produk_id,
                'deskripsi' => $request->deskripsi,
                'harga' => $requestProduk['harga'],
                'warna' => $requestProduk['warna'],
                'jumlah_stok' => $requestProduk['jumlah_stok'],
                'ukuran' => $requestProduk['ukuran'],
                'panjang' => $requestProduk['panjang'],
                'lebar' => $requestProduk['lebar'],
                'tinggi' => $requestProduk['tinggi'],
                'berat' => $requestProduk['berat'],
            ];

            if ($parent) {
                $input['parent_produk_id'] = $parent->id;
            }

            $produkChild = Produk::create($input);

            if (! $parent) {
                $parent = $produkChild;
            }

            if (array_key_exists('pathImages', $requestProduk)) {
                foreach ($requestProduk['pathImages'] as $requestNewImagePath) {
                    if (Storage::exists($requestNewImagePath)) {
                        $extension = explode('.', $requestNewImagePath);
                        $extension = end($extension);
                        $name = Str::random(40) .'.'.$extension;
                        $newPath = 'public/produk/images/'.$name;
                        Storage::move($requestNewImagePath, $newPath);
    
                        $produkChild->gambar()->create([
                            'path' => $newPath,
                        ]);
                    }
                }
            }
        }

        DB::commit();
        return to_route('produk.index')->with('message', toastSuccess("Data berhasil ditambah"));
    }

    public function show($id)
    {
        $produk = Produk::select(['id', 'nama', 'kategori_produk_id', 'deskripsi'])
            ->findOrFail($id);

        $listProduk =  Produk::where('id', $id)
            ->orWhere('parent_produk_id', $id)
            ->get([
                'id',
                'harga',
                'warna',
                'jumlah_stok',
                'ukuran',
                'panjang',
                'lebar',
                'tinggi',
                'berat',
            ]);

        $produk->load(['kategori:id,nama']);
        $listProduk->load(['gambar:id,produk_id,path']);

        $listGambar = $listProduk
            ->map(function($val) {
                $el = $val['gambar'];
                return $el;
            })
            ->flatten();
        $produk->listProduk = $listProduk->toArray();

        return Inertia::render('Admin/Produk/Show', [
            'data' => $produk,
            'listGambar' => $listGambar
        ]);
    }

    public function edit($id)
    {
        $kategoriProduk = KategoriProduk::all(['id', 'nama']);
        $produk = Produk::select(['id', 'nama', 'kategori_produk_id', 'deskripsi'])
            ->findOrFail($id);
        $listProduk =  Produk::where('id', $id)
            ->orWhere('parent_produk_id', $id)
            ->get([
                'id',
                'harga',
                'warna',
                'jumlah_stok',
                'ukuran',
                'panjang',
                'lebar',
                'tinggi',
                'berat',
            ]);

        $listProduk->load(['gambar:id,produk_id,path',]);

        $produk->listProduk = $listProduk->toArray();

        return Inertia::render('Admin/Produk/Edit', [
            'data' => $produk,
            'kategori' => $kategoriProduk,
        ]);
    }

    public function update(UpdateProdukRequest $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->update([
            'nama' => $request->nama,
            'kategori_produk_id' => $request->kategori_produk_id,
            'deskripsi' => $request->deskripsi,
        ]);

        foreach($request->listProduk as $requestProduk) {
            if (array_key_exists('id', $requestProduk)) {
                $produkChild = Produk::find($requestProduk['id']);

                if ($produkChild) {
                    $produkChild->update([
                        'nama' => $request->nama,
                        'kategori_produk_id' => $request->kategori_produk_id,
                        'deskripsi' => $request->deskripsi,
                        'harga' => $requestProduk['harga'],
                        'warna' => $requestProduk['warna'],
                        'jumlah_stok' => $requestProduk['jumlah_stok'],
                        'ukuran' => $requestProduk['ukuran'],
                        'panjang' => $requestProduk['panjang'],
                        'lebar' => $requestProduk['lebar'],
                        'tinggi' => $requestProduk['tinggi'],
                        'berat' => $requestProduk['berat'],
                    ]);
                }
            } else {
                $produkChild = Produk::create([
                    'nama' => $request->nama,
                    'kategori_produk_id' => $request->kategori_produk_id,
                    'deskripsi' => $request->deskripsi,
                    'harga' => $requestProduk['harga'],
                    'warna' => $requestProduk['warna'],
                    'jumlah_stok' => $requestProduk['jumlah_stok'],
                    'ukuran' => $requestProduk['ukuran'],
                    'panjang' => $requestProduk['panjang'],
                    'lebar' => $requestProduk['lebar'],
                    'tinggi' => $requestProduk['tinggi'],
                    'berat' => $requestProduk['berat'],
                    'parent_produk_id' => $produk->id,
                ]);
            }

            if (array_key_exists('pathImages', $requestProduk)) {
                foreach ($requestProduk['pathImages'] as $requestNewImagePath) {
                    if (Storage::exists($requestNewImagePath)) {
                        $extension = explode('.', $requestNewImagePath);
                        $extension = end($extension);
                        $name = Str::random(40) .'.'.$extension;
                        $newPath = 'public/produk/images/'.$name;
                        Storage::move($requestNewImagePath, $newPath);
    
                        $produkChild->gambar()->create([
                            'path' => $newPath,
                        ]);
                    }
                }
            }
        }

        return to_route('produk.index')->with('message', toastSuccess("Data berhasil diubah"));
    }

    public function destroy($id)
    {

        try {
            DB::beginTransaction();

            $current = Produk::findOrFail($id);
            
            $child = Produk::where('parent_produk_id', $id)->first();

            if ($child) {
                Produk::where('parent_produk_id', $id)
                    ->where('id', '!=', $child->id)
                    ->update([
                        'parent_produk_id' => $child->id,
                    ]);
                $child->parent_produk_id = null;
                $child->save();
            }

            $current->delete();
    
            DB::commit();
    
            return back()->with('message', toastSuccess('Data berhasil dihapus'));
        } catch (Exception $th) {
            DB::rollBack();
            return back()->with('message', toastFailed('Tidak dapat dihapus karena masih digunakan'));
    
        }

    }
}
