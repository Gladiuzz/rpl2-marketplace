<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Penjual;
use App\Models\Produk;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PenjualController extends Controller
{
    public function daftarSebagaiSeller(Request $request)
{
    try {
        $this->validate($request, [
            'nama_toko' => ['required'],
            'alamat_toko' => ['required'],
        ]);

            $user = Auth::user();
            $data = $request->except('_token');
            $data['id_user'] = $user->id;

            if ($user->penjual != null) {
                return ResponseFormatter::error(
                    [
                        'message' => 'User sudah menjadi penjual dengan toko bernama ' . $user->penjual->nama_toko,
                    ],
                    'Error',
                    500
                );
            }

        $penjual = Penjual::create($data);

        if ($user->role != 'Admin') {
            $user->role = 'Penjual';
            $user->update();
        }

            return ResponseFormatter::success(
                $penjual,
                'Berhasil menjadi penjual'
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

    public function indexPenjual($id_penjual)
    {
        $penjual = Penjual::with(['user'])
        ->where('id', $id_penjual)
        ->first();

        return ResponseFormatter::success(
            $penjual,
            'Berhasil data penjual'
        );

    }

    public function updatePenjual($id_penjual, Request $request)
    {
        try {
            $this->validate($request, [
                'nama_toko' => ['required'],
                'alamat_toko' => ['required'],
            ]);

            $data = $request->except('_token');
            $penjual = Penjual::findorFail($id_penjual);

            $penjual->update($data);

            return ResponseFormatter::success(
                $penjual,
                'Berhasil Update toko'
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

    public function listProdukPenjual($id_penjual)
    {
        // $penjual = Penjual::where('id_user', Auth::user()->id);
        $produk = Produk::where('id_penjual', $id_penjual)->get();

        if ($produk) {
            return ResponseFormatter::success(
                $produk,
                'Berhasil mengambil list data produk anda'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data produk tidak ada',
                404,
            );
        }
    }
}
