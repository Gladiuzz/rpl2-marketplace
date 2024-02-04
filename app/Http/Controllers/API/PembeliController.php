<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PembeliController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return ResponseFormatter::success(
            $user,
            'Berhasil mendapatkan data'
        );
    }

    public function update(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $this->validate($request, [
                'nama' => ['required'],
                'username' => ['required', 'unique:users,username,' . $id],
                'email' => ['required', 'unique:users,email,' . $id, 'email'],
                'no_telepon' => ['required', 'numeric'],
                // 'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);


            $pembeli = User::findorFail($id);

            $data = $request->except('_token');
            if (!empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                $data['password'] = $pembeli->password;
            }

            $avatar = $request->file('avatar');


            if ($request->hasFile('avatar')) {
                $old_path = 'public/user/' . $pembeli->avatar;
                Storage::delete($old_path);

                $file_name = time() . "_" . $avatar->getClientOriginalName();
                $avatar->storeAs('public/user', $file_name);

                $data['avatar'] = $file_name;
            }

            $pembeli->update($data);

            return ResponseFormatter::success(
                $pembeli,
                'Berhasil Update Data'
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
