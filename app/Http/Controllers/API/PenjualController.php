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
            $this->validate($request,[
                'nama_toko' => ['required'],
                'alamat_toko' => ['required'],
            ]);

            $user = Auth::user();
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
}
