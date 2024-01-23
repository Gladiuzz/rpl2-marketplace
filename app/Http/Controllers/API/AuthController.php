<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $this->validate($request, [
                'nama' => ['required'],
                'username' => ['required'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'min:6'],
                'no_telepon' => ['required', 'numeric'],
            ]);

            $data = $request->except('_token');
            $data['password'] = bcrypt($data['password']);

            $user = User::create($data);

            $tokenResult = $user->createToken('authToken');
            $tokenResult->expires_at = now()->addHour();

            return ResponseFormatter::success([
                'access_token' => $tokenResult->plainTextToken,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'User berhasil dibuat');
        } catch (ValidationException $e) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $e->validator->errors(),
                ],
                'Error',
                400
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

    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'identity' => ['required'],
                'password' => ['required'],
            ]);

            $loginType = filter_var($request->input('identity'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $data = $request->except('_data');

            $credentials = [
                $loginType => $data['identity'],
                'password' => $data['password'],
            ];


            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $tokenResult = $user->createToken('authToken');
                $tokenResult->expires_at = now()->addHour();

                return ResponseFormatter::success([
                    'access_token' => $tokenResult->plainTextToken,
                    'token_type' => 'Bearer',
                    'user' => $user
                ], 'Login Berhasil');
            }
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

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return ResponseFormatter::success(
            'Logout Berhasil'
        );
    }
}
