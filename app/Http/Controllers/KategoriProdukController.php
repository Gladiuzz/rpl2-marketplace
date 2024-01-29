<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use Illuminate\Http\Request;

class KategoriProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = KategoriProduk::all();

        return view('admin.kategori.index', compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.kategori.manage');
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
            'nama' => ['required', 'string'],
            'deskripsi' => ['required'],
        ]);

        $data = $request->except('_token');
        KategoriProduk::create($data);

        return redirect()->route('kategori.index')->with('success', 'Kategori Produk Berhasil Dibuat');
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
        $kategori = KategoriProduk::findorFail($id);

        return view('admin.kategori.manage', compact('kategori'));
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
            'nama' => ['required', 'string'],
            'deskripsi' => ['required'],
        ]);

        $data = $request->except('_token');
        $kategori = KategoriProduk::findorFail($id);

        $kategori->update($data);

        return redirect()->route('kategori.index')->with('success', 'Kategori Produk Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kategori = KategoriProduk::findorFail($id);

        if ($kategori->produk()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori yang dipilih masih berhubungan dengan produk');
        } else {
            $kategori->delete();
        }

        return redirect()->route('kategori.index')->with('success', 'Kategori Produk Berhasil Dihapus');
    }
}
