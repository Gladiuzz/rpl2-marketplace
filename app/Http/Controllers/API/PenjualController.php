<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Penjual;
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

       
        if ($user->penjual && $user->penjual->exists()) {
            return ResponseFormatter::error(
                [
                    'message' => 'User sudah menjadi penjual dengan toko bernama ' . $user->penjual->nama_toko,
                ],
                'Error',
                500
            );
        }

        $data = $request->except('_token');
        $data['id_user'] = $user->id;

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
public function show($id_user)
{
    try {
        $penjual = Penjual::where('id_user', $id_user)->first();

        if ($penjual) {
            return ResponseFormatter::success(
                $penjual,
                'Berhasil mengambil data Toko'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data toko tidak ditemukan',
                404
            );
        }
    } catch (\Exception $e) {
        return ResponseFormatter::error(
            null,
            'Terjadi kesalahan',
            500
        );
    }
}



}
