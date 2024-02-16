<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Keranjang;
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
                'alamat' => ['required'],
            ]);

            $keranjang = Keranjang::where('id_user', Auth::user()->id)->get();


            $data = $request->except('_token');
            $data['id_user'] = Auth::user()->id;
            $data['invoice_number'] = $this->formatDateAndInvoiceNumber()['invoice_number'];
            $data['tanggal_pesanan'] = $this->formatDateAndInvoiceNumber()['date'];
            $data['status'] = 'Pending';

            $pesanan = Pesanan::create($data);
            foreach ($keranjang as $key => $value) {
                $pesanan_produk = array(
                    'id_pesanan' => $pesanan->id,
                    'id_produk' => $value->id_produk,
                    'jumlah_produk' => $value->qty_produk,
                );

                PesananProduk::create($pesanan_produk);

                $value->delete();
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
        $pesanan = Pesanan::with(['pesananProduk.produk'])
            ->where('id_user', $id)->get();

        if ($pesanan) {
            return ResponseFormatter::success(
                $pesanan,
                'Berhasil mengambil data transaksi'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data transaksi tidak ada',
                404,
            );
        }
    }
    public function detailHistoryTransaksi($id)
    {
        $id_user = Auth::user()->id;
        $pesanan = Pesanan::with(['pesananProduk.produk'])
            ->where('id_user', $id_user)->find($id);

        if ($pesanan) {
            return ResponseFormatter::success(
                $pesanan,
                'Berhasil mengambil data transaksi'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data transaksi tidak ada',
                404,
            );
        }
    }

    public function historyTransaksiPenjual()
    {
        $penjual = Auth::user()->penjual;
        $pesanan = Pesanan::with(['pesananProduk.produk'])
            ->whereHas('pesananProduk', function ($query) use ($penjual) {
                $query->whereHas('produk', function ($value) use ($penjual) {
                    $value->where('id_penjual', $penjual->id);
                });
            })->get();

        if ($pesanan) {
            return ResponseFormatter::success(
                $pesanan,
                'Berhasil mengambil data transaksi'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data transaksi tidak ada',
                404,
            );
        }
    }

    public function detailHistoryTransaksiPenjual($id)
    {
        $penjual = Auth::user()->penjual;
        $pesanan = Pesanan::with(['pesananProduk.produk'])
            ->whereHas('pesananProduk', function ($query) use ($penjual) {
                $query->whereHas('produk', function ($value) use ($penjual) {
                    $value->where('id_penjual', $penjual->id);
                });
            })->find($id);

        if ($pesanan) {
            return ResponseFormatter::success(
                $pesanan,
                'Berhasil mengambil data transaksi'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data transaksi tidak ada',
                404,
            );
        }
    }

    public function updateStatusPesananPenjual($id)
    {
        $penjual = Auth::user()->penjual;
        $pesanan = Pesanan::with(['pesananProduk.produk'])
            ->whereHas('pesananProduk', function ($query) use ($penjual) {
                $query->whereHas('produk', function ($value) use ($penjual) {
                    $value->where('id_penjual', $penjual->id);
                });
            })->find($id);

        switch ($pesanan->status) {
            case 'Pending':
                $this->cekPenjualTerkait($pesanan, 'Dikonfirmasi', 'Pending');
                break;
            case 'Dikonfirmasi':
                foreach ($pesanan->pesananProduk as $key => $value) {
                    $value['status'] = 'Diproses';
                    $value->update();
                }
                $this->cekPenjualTerkait($pesanan, 'Diproses', 'Diproses');
                break;
            case 'Diproses':
                foreach ($pesanan->pesananProduk as $key => $value) {
                    $value['status'] = 'Dikirim';
                    $value->update();
                }
                $this->cekPenjualTerkait($pesanan, 'Dikirim', 'Dikirim');
                break;
            default:
                return ResponseFormatter::error(
                    null,
                    'Transaksi sudah dikonfirmasi',
                    404,
                );
                break;
        }



        return ResponseFormatter::success(
            'Berhasil update status transaksi'
        );
    }

    public function updateStatusPesananUser($id)
    {
        $pesanan = Pesanan::with(['pesananProduk.produk', 'pembayaran'])
            ->find($id);



        switch ($pesanan->pembayaran->metode) {
            case 'COD':
                $this->updateStatusCOD($pesanan);
                break;
            default:
                return ResponseFormatter::error(
                    null,
                    'Metode Pembayaran Tidak Valid atau transaksi sudah selesai',
                    404,
                );
                break;
        }

        if ($pesanan->status != 'Selesai') {
            return ResponseFormatter::success(
                $pesanan,
                'Berhasil update status transaksi & produk transaksi'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Transaksi sudah selesai',
                404,
            );
        }
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

    private function updateStatusCOD(Pesanan $pesanan)
    {
        switch ($pesanan->status) {
            case 'Dikirim':
                foreach ($pesanan->pesananProduk as $key => $value) {
                    $value['status'] = 'Diterima';
                    $value->update();
                }
                $pesanan->status = 'Diterima';
                break;
            case 'Diterima':
                $pesanan->pembayaran->status = 'Sudah Dibayar';

                foreach ($pesanan->pesananProduk as $key => $value) {
                    $value['status'] = 'Dibayar';
                    $value->update();
                }
                $pesanan->status = 'Dibayar';
                $pesanan->pembayaran->update();
                break;
            case 'Dibayar':
                foreach ($pesanan->pesananProduk as $key => $value) {
                    $value['status'] = 'Selesai';
                    $value->update();
                }
                $pesanan->status = 'Selesai';
                break;
            default:
                return ResponseFormatter::error(
                    null,
                    'Transaksi sudah selesai',
                    404,
                );
                break;
        }

        $pesanan->update();
    }

    public function cekPenjualTerkait(Pesanan $pesanan, $status, $statusProduk)
    {
        $allStatus = $pesanan->pesananProduk->where('status', $statusProduk)->count() == $pesanan->pesananProduk->count();
        if ($allStatus) {
            $pesanan->status = $status;
            $pesanan->update();
        }
    }

}
