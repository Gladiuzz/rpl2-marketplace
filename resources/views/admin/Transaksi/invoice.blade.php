@extends('layouts.app')

@section('title', 'Data Transaction')

@section('css')
    <link href="{{ asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">

@endsection

@section('content')
    <div class="ibox-content p-xl">
        <div class="row">
            <div class="col-sm-6">
                <h4>Invoice No.</h4>
                <h4 class="text-navy">{{ $transaksi->invoice_number }}</h4>
                <span>To:</span>
                <address>
                    <strong>{{ $transaksi->user->nama }}</strong><br>
                    {{ $transaksi->user->alamat }}<br>
                    <abbr title="Phone">P:</abbr> {{ $transaksi->user->no_telepon }}
                </address>
                <p>
                    <span><strong>Tanggal Transaksi:</strong> {{ $transaksi->getTanggal() }}</span><br>
                    {{-- <span><strong>Due Date:</strong> March 24, 2014</span> --}}
                </p>
            </div>

            <div class="col-sm-6 text-right">
                {{--  --}}
            </div>
        </div>

        <div class="table-responsive m-t">
            <table class="table invoice-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Quantity</th>
                        <th>Harga</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi->pesananProduk as $item)
                        <tr>
                            <td>
                                <div><strong>{{ $item->produk->nama }}</strong></div>
                                {{-- <small>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua.</small> --}}
                            </td>
                            <td>{{ $item->jumlah_produk }}</td>
                            <td>@currency($item->produk->harga)</td>
                            <td>@currency($item->produk->harga * $item->jumlah_produk)</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div><!-- /table-responsive -->

        <table class="table invoice-total">
            <tbody>

                <tr>
                    <td><strong>TOTAL :</strong></td>
                    <td>@currency($transaksi->total_harga)</td>
                </tr>
            </tbody>
        </table>
        {{-- <div class="text-right">
            <button class="btn btn-primary"><i class="fa fa-dollar"></i> Make A Payment</button>
        </div> --}}

        {{-- <div class="well m-t"><strong>Comments</strong>
            It is a long established fact that a reader will be distracted by the readable content of a page when looking at
            its layout. The point of using Lorem Ipsum is that it has a more-or-less
        </div> --}}
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.dataTables-example').DataTable({
                pageLength: 10,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [{
                        extend: 'copy'
                    },
                    {
                        extend: 'csv'
                    },
                    {
                        extend: 'excel',
                        title: 'Transaction'
                    },
                    {
                        extend: 'pdf',
                        title: 'Transaction'
                    },

                    {
                        extend: 'print',
                        customize: function(win) {
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }
                    }
                ]

            });

        });
    </script>
@endsection
