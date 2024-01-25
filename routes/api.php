<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\KategoriProdukController;
use App\Http\Controllers\API\PenjualController;
use App\Http\Controllers\API\ProdukController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1'], function() {
    // login
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    // list kategori produk
    Route::get('kategori-produk', [KategoriProdukController::class, 'index']);

    // list produk
    Route::get('produk', [ProdukController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function() {
        // logout
        Route::post('logout', [AuthController::class, 'logout']);

        // kategori produk
        Route::post('kategori-produk', [KategoriProdukController::class, 'store']);
        Route::get('kategori-produk/{id}', [KategoriProdukController::class, 'show']);
        Route::put('kategori-produk/{id}', [KategoriProdukController::class, 'update']);

        // produk
        Route::post('produk', [ProdukController::class, 'store']);
        Route::get('produk/{id}', [ProdukController::class, 'show']);
        Route::put('produk/{id}', [ProdukController::class, 'update']);
        Route::delete('produk/{id}', [ProdukController::class, 'destroy']);

        // penjual
        Route::post('penjual-register', [PenjualController::class, 'daftarSebagaiSeller']);
    });

});
