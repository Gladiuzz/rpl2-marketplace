<?php

use App\Http\Controllers\KategoriProdukController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::group(['middleware' => 'admin'], function() {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Produk
    Route::resource('produk', ProdukController::class);
    Route::get('produk/{id}/status', [ProdukController::class, 'updateStatus'])->name('produk.update.status');

    // Kategori
    Route::resource('kategori', KategoriProdukController::class);

    // Transaksi
    Route::resource('transaksi', TransaksiController::class);

    // Penjual
    Route::resource('user-penjual', PenjualController::class);

    // Pembeli
    Route::resource('user-pembeli', PembeliController::class);

});
