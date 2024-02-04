<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\PesananProduk;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TransaksiController extends Controller
{


    public function membuatPesanan(Request $request)
    {
        try {
            $this->validate($request, [
                'total_harga' => ['required'],
                'keranjang' => ['required','array'],
            ]);

            $data = $request->except('_token');
            $data['id_user'] = Auth::user()->id;
            $data['invoice_number'] = $this->formatDateAndInvoiceNumber()['invoice_number'];
            $data['tanggal_pesanan'] = $this->formatDateAndInvoiceNumber()['date'];
            $data['status'] = 'Pending';

            $pesanan = Pesanan::create($data);
            foreach ($data['keranjang'] as $key => $value) {
                $pesanan_produk = array(
                    'id_pesanan' => $pesanan->id,
                    'id_produk' => $value['id'],
                    'jumlah_produk' => $value['jumlah_produk'],
                );

                PesananProduk::create($pesanan_produk);
            }
            $data_pembayaran = array(
                'id_pesanan' => $pesanan->id,
                'metode' => 'COD',
                'status' => 'Belum Dibayar',
            );

            $pembayaran = Pembayaran::create($data_pembayaran);

            return ResponseFormatter::success(
                array(
                    'pesanan' => $pesanan,
                    'pembayaran' => $pembayaran,
                ),
                'Pesanan berhasil dibuat',
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

    public function historyTransaksi()
    {
        $id = Auth::user()->id;
        $pesanan = Pesanan::where('id_user', $id)->get();

        return ResponseFormatter::success(
            $pesanan,
            'Berhasil data transaksi'
        );
    }

    private function formatDateAndInvoiceNumber()
    {
        $today = Carbon::now();

        $formattedDate = $today->format('Ymd');

        $uniqueNumber = random_int(1, 1000);

        $invoiceNumber = $formattedDate . '' . round($uniqueNumber);

        $data = array(
            'date' => $today,
            'invoice_number' => $invoiceNumber
        );

        return $data;
    }
}
