<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use RealRashid\SweetAlert\Facades\Alert;
use Storage;
use DB;

class PemesananController extends Controller
{
    public function index(request $request)
    {
       $data = Pemesanan::all();
      //  dd($data);
       
       $data = $data->map(function($value) { 
          $value->gambar = Storage::url($value->gambar);
          return $value;
          });
       return view('pemesanan.index', compact('data')) ;
    }
 
    public function create(){
       $pemesanan = Pemesanan::count();
       return view('pemesanan.create', compact('pemesanan'));
    }
 
    public function store(Request $request)
    {
          $this->validate($request, [
             'gambar' => 'required',
             'nama_produk' => 'required',
             'harga' => 'required',
             'ukuran' => 'required',
             'warna' => 'required',
             'stok' => 'required',
             'deskripsi' => 'required',
          ]);
          
          $pemesanan = Pemesanan::create([
             'gambar' => '',
             'nama_produk' => $request->nama_produk,
             'harga' => $request->harga,
             'ukuran' => $request->ukuran,
             'warna' => $request->warna,
             'stok' => $request->stok,
             'deskripsi' => $request->deskripsi,
          ]);
 
          if($request->hasfile('gambar')){
             $path = $request->file('gambar')->store('public/gambar');
             $pemesanan->gambar = $path;
             $pemesanan->save();
         }
 
       return redirect()->route('pemesanan.index')->with('success', 'Data Berhasil Ditambahkan');
    }  
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\pemesanan $pemesanan
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
       $data = Pemesanan::find($id);
       $pemesanan = pemesanan::count();
       return view('pemesanan.edit', compact('data', 'pemesanan'));
    }
 
     /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\pemesanan $pemesanan
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request, $id)
     {
       
 
       $pemesanan = Pemesanan::find($id);
         $pemesanan->nama_produk = $request->nama_produk;
         $pemesanan->harga = $request->harga;
         $pemesanan->ukuran = $request->ukuran;
         $pemesanan->warna = $request->warna;
         $pemesanan->stok = $request->stok;
         $pemesanan->deskripsi = $request->deskripsi;
 
         if($request->hasfile('gambar')){
          Storage::delete($pemesanan->gambar);
          $path = $request->file('gambar')->store('public/gambar');
          $pemesanan->gambar = $path;
          $pemesanan->save();
      }
            
         $pemesanan->save(); 
         return redirect()->route('pemesanan.index')->with('success', 'Data Berhasil Diubah');
 
     }
     
     /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\pemesanan $pemesanan
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
     {
         $pemesanan = Pemesanan::find($id);
         $pemesanan->delete();
         return redirect()->route('pemesanan.index')->with('success', 'Data Berhasil Dihapus');
     }
}
