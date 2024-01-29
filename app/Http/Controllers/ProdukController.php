<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use App\Models\Penjual;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produk = Produk::all();

        return view('admin.produk.index', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategori = KategoriProduk::all();
        $penjual = Penjual::all();

        $data = array(
            'kategori' => $kategori,
            'penjual' => $penjual,
        );

        return view('admin.produk.manage', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id_kategori' => ['required'],
            'nama' => ['required'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'harga' => ['required', 'numeric', 'min:1'],
            'gambar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'deskripsi' => ['required'],
        ]);

        $data = $request->except('_token');
        $data['id_penjual'] = Auth::user()->id;
        $gambar = $request->file('gambar');

        if ($request->hasFile('gambar')) {
            $file_name = time() . "_" . $gambar->getClientOriginalName();
            $gambar->storeAs('public/produk', $file_name);

            $data['gambar'] = $file_name;
            Produk::create($data);

            return redirect()->route('produk.index')->with('success', 'Produk Berhasil Ditambah');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $produk = Produk::findorFail($id);
        $kategori = KategoriProduk::all();
        $penjual = Penjual::all();


        $data = array(
            'produk' => $produk,
            'kategori' => $kategori,
            'penjual' => $penjual,
        );

        return view('admin.produk.manage', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_kategori' => ['required'],
            'nama' => ['required'],
            'jumlah' => ['required', 'numeric'],
            'harga' => ['required', 'numeric'],
            // 'gambar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $data = $request->except('_token');
        $produk = Produk::findorFail($id);

        $gambar = $request->file('gambar');

        if (!empty($gambar)) {
            $old_path = 'public/produk/' . $produk->gambar;
            Storage::delete($old_path);

            $file_name = time() . "_" . $gambar->getClientOriginalName();
            $gambar->storeAs('public/produk', $file_name);

            $data['gambar'] = $file_name;
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produk = Produk::findorFail($id);
        $old_path = 'public/produk/' . $produk->gambar;
        Storage::delete($old_path);

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk Berhasil Dihapus');
    }
}
