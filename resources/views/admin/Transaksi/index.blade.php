@extends('layouts.app')

@section('title', 'Data Transaction')

@section('css')
    <link href="{{ asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">

@endsection

@section('content')
    <div class="ibox ">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="ibox-title">
            <h5>Data Transaction</h5>
            <div class="ibox-tools">
                {{-- <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-wrench"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="#" class="dropdown-item">Config option 1</a>
                </li>
                <li><a href="#" class="dropdown-item">Config option 2</a>
                </li>
            </ul>
            <a class="close-link">
                <i class="fa fa-times"></i>
            </a> --}}
            </div>
        </div>
        <div class="ibox-content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables-example">
                    <thead>
                        <tr>
                            <th>Nomor Invoice</th>
                            <th>Tanggal Pesanan</th>
                            <th>Pembeli</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Metode Pembayaran</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pesanan as $item)
                            <tr class="gradeX">
                                <td>{{ $item->getTanggal() }}</td>
                                <td>{{ $item->invoice_number }}</td>
                                <td>{{ $item->user->nama }}</td>
                                <td>@currency($item->total_harga)</td>
                                <td class={{ $item->status == 'Cancelled' ? 'text-danger' : 'text-navy' }}>
                                    Pending</td>
                                <td>{{ $item->pembayaran->metode }}</td>
                                <td>
                                    <a href="{{ route('transaksi.show', ['transaksi' => $item->id]) }}"><i
                                            class='fa btn btn-primary fa-eye'></i></a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                    {{-- @if (Auth::user()->role == 'seller')
                        <tfoot>
                            <td class="bg-warning text-white" colspan="3">Total Penjualan</td>
                            <td class="bg-warning text-white" colspan="3">@currency($totalTransaction)</td>
                        </tfoot>
                    @endif --}}
                </table>
            </div>
        </div>
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
