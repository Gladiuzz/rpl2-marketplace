<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Produk;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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

        if ($produk) {
            return ResponseFormatter::success(
                $produk,
                'Berhasil mengambil list data produk'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data produk tidak ada',
                404,
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'id_kategori' => ['required'],
                'nama' => ['required'],
                'jumlah' => ['required', 'numeric'],
                'harga' => ['required', 'numeric'],
                'gambar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            $data = $request->except('_token');
            $data['id_penjual'] = Auth::user()->penjual->id;
            $gambar = $request->file('gambar');

            if ($request->hasFile('gambar')) {
                $file_name = time() . "_" . $gambar->getClientOriginalName();
                $gambar->storeAs('public/produk', $file_name);

                $data['gambar'] = $file_name;
                $produk = Produk::create($data);
            }


            return ResponseFormatter::success(
                $produk,
                'Berhasil membuat produk'
            );
        } catch (ValidationException $e) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $e->validator->errors(),
                ],
                'Error',
                500
            );
        } catch (Exception $error) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $error->getMessage(),
                ],
                'Error',
                500
            );
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
        $produk = Produk::findorFail($id);

        if ($produk) {
            return ResponseFormatter::success(
                $produk,
                'Berhasil mengambil data produk'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data produk tidak ada',
                404,
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        try {
            $this->validate($request, [
                'id_kategori' => ['required'],
                'nama' => ['required'],
                'jumlah' => ['required', 'numeric'],
                'harga' => ['required', 'numeric'],
                // 'gambar' => ['required','image','mimes:jpeg,png,jpg,gif','max:2048'],
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

            return ResponseFormatter::success(
                $produk,
                'Berhasil update produk'
            );
        } catch (ValidationException $e) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $e->validator->errors(),
                ],
                'Error',
                500
            );
        } catch (Exception $error) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $error->getMessage(),
                ],
                'Error',
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $produk = Produk::findorFail($id);
            $old_path = 'public/produk/' . $produk->gambar;
            Storage::delete($old_path);

            $produk->delete();

            return ResponseFormatter::success(
                'Berhasil Menghapus produk'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $error->getMessage(),
                ],
                'Error',
                500
            );
        }
    }
}
