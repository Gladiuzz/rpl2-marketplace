<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

        if ($kategori) {
            return ResponseFormatter::success(
                $kategori,
                'Berhasil mengambil list data kategori produk'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data kategori produk tidak ada',
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
                'nama' => ['required'],
                'deskripsi' => ['required'],
            ]);

            $data = $request->except('_token');
            $kategori = KategoriProduk::create($data);

            return ResponseFormatter::success(
                $kategori,
                'Berhasil membuat kategori produk'
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
        $kategori = KategoriProduk::findorFail($id);

        if ($kategori) {
            return ResponseFormatter::success(
                $kategori,
                'Berhasil mengambil data kategori produk'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data kategori produk tidak ada',
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
                'nama' => ['required'],
                'deskripsi' => ['required'],
            ]);

            $data = $request->except('_token');
            $kategori = KategoriProduk::findorFail($id);

            $kategori->update($data);

            return ResponseFormatter::success(
                $kategori,
                'Berhasil update kategori produk'
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
            $produk = KategoriProduk::findorFail($id);

            $produk->delete();

            return ResponseFormatter::success(
                'Berhasil Menghapus kategori produk'
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
