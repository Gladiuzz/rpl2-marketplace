<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class KeranjangController extends Controller
{
    public function index()
    {
        $id = Auth::user()->id;
        $keranjang = Keranjang::with('produk')
        ->where('id_user', $id)
        ->get();

        if ($keranjang) {
            return ResponseFormatter::success(
                $keranjang,
                'Berhasil mengambil keranjang'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Keranjang kosong',
                404,
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'id_produk' => ['required'],
                'qty_produk' => ['required','numeric'],
            ]);

            $data = $request->except('_token');
            $data['id_user'] = Auth::user()->id;

            $keranjang = Keranjang::create($data);

            return ResponseFormatter::success(
                $keranjang,
                'Produk berhasil dimasukkan kedalam keranjang',
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

    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'qty_produk' => ['required','numeric'],
            ]);

            $keranjang = Keranjang::findorFail($id);
            $data = $request->except('_token');

            $keranjang->update($data);

            return ResponseFormatter::success(
                $keranjang,
                'quantity produk pada keranjang berhasil di update',
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
}
