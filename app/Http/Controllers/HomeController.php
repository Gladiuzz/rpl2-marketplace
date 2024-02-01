<?php

namespace App\Http\Controllers;

use App\Models\Penjual;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $penjual = Penjual::count();
        $transaksi = Pesanan::count();

        $data = array(
            'penjual' => $penjual,
            'transaksi' => $transaksi,
        );

        return view('home', $data);
    }
}
