<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $this->validate($request, [
                'nama' => ['required'],
                'username' => ['required', 'unique:users,username'],
                'email' => ['required', 'unique:users,email', 'email'],
                'password' => ['required', 'min:6'],
                'no_telepon' => ['required', 'numeric'],
            ]);

            $data = $request->except('_token');
            $data['password'] = bcrypt($data['password']);
            $data['role'] = 'User';

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
                'username' => ['required'],
                'password' => ['required'],
            ]);

            $loginType = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $data = $request->except('_data');

            $credentials = [
                $loginType => $data['username'],
                'password' => $data['password'],
            ];



            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                if (!Hash::check($request->password, $user->password,[])) {
                    throw new \Exception('Invalid Credentials');
                }
                $tokenResult = $user->createToken('authToken');
                $tokenResult->expires_at = now()->addHour();

                return ResponseFormatter::success([
                    'access_token' => $tokenResult->plainTextToken,
                    'token_type' => 'Bearer',
                    'user' => $user
                ], 'Login Berhasil');
            } else {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized',
                ], 'Authentication Failed', 500);
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
