<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\KategoriProdukController;
use App\Http\Controllers\API\KeranjangController;
use App\Http\Controllers\API\PembeliController;
use App\Http\Controllers\API\PenjualController;
use App\Http\Controllers\API\ProdukController;
use App\Http\Controllers\API\TransaksiController;
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

Route::group(['prefix' => 'v1'], function () {
    // login
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    // list kategori produk
    Route::get('kategori-produk', [KategoriProdukController::class, 'index']);

    // list produk
    Route::get('produk', [ProdukController::class, 'index']);
    Route::get('produk/{id}', [ProdukController::class, 'show']);

    // list Penjual
    Route::get('penjual', [PenjualController::class, 'AllPenjual']);
    Route::get('penjual/{id_penjual}', [PenjualController::class, 'indexPenjual']);


    Route::middleware(['auth:sanctum'])->group(function () {
        // user
        Route::get('pembeli', [PembeliController::class, 'index']);
        Route::put('pembeli', [PembeliController::class, 'update']);

        // keranjang
        Route::get('keranjang', [KeranjangController::class, 'index']);
        Route::post('keranjang', [KeranjangController::class, 'store']);
        Route::put('keranjang/{id}', [KeranjangController::class, 'update']);

        // Transaksi/Pesanan
        Route::get('transaksi', [TransaksiController::class, 'historyTransaksi']);
        Route::get('transaksi/{id}', [TransaksiController::class, 'detailHistoryTransaksi']);
        Route::post('transaksi', [TransaksiController::class, 'membuatPesanan']);
        Route::get('transaksi/{id}/update-status', [TransaksiController::class, 'updateStatusPesananUser']);

        // logout
        Route::post('logout', [AuthController::class, 'logout']);

        // daftar penjual
        Route::post('penjual-register', [PenjualController::class, 'daftarSebagaiSeller']);

        Route::middleware('penjual')->group(function () {
            // Commend sementara
            // Route::post('kategori-produk', [KategoriProdukController::class, 'store']);
            // Route::get('kategori-produk/{id}', [KategoriProdukController::class, 'show']);
            // Route::put('kategori-produk/{id}', [KategoriProdukController::class, 'update']);
            // Route::post('kategori-produk/{id}', [KategoriProdukController::class, 'destroy']);

            // produk
            Route::post('produk', [ProdukController::class, 'store']);
            Route::put('produk/{id}', [ProdukController::class, 'update']);
            Route::delete('produk/{id}', [ProdukController::class, 'destroy']);

            // penjual

            Route::put('penjual/{id_penjual}', [PenjualController::class, 'updatePenjual']);
            Route::get('penjual/{id_penjual}/produk', [PenjualController::class, 'listProdukPenjual']);

            // Transaksi/Pesanan
            Route::get('transaksi/{id}/status', [TransaksiController::class, 'updateStatusPesananPenjual']);
            Route::get('transaksi/{id}/penjual', [TransaksiController::class, 'detailHistoryTransaksiPenjual']);
            Route::get('transaksi/penjual', [TransaksiController::class, 'historyTransaksiPenjual']);
        });
    });
});
