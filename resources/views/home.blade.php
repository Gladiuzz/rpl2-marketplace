@extends('layouts.app')

@section('title', 'Dashboard')

@section('css')
    <link href="{{ asset('registered/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header">{{ __('Dashboard') }}</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 mx-0">
                        <div class="widget style1 blue-bg">
                            <div class="row">
                                <div class="col-4 text-center">
                                    <i class="fa fa-clipboard fa-4x"></i>
                                </div>
                                <div class="col-8 text-right">
                                    <span> Total Transaksi</span>
                                    <h3 class="font-bold">{{ $transaksi }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mx-0">
                        <div class="widget style1 blue-bg">
                            <div class="row">
                                <div class="col-4 text-center">
                                    <i class="fa fa-user-circle fa-4x"></i>
                                </div>
                                <div class="col-8 text-right">
                                    <span> Total Penjual</span>
                                    <h3 class="font-bold">{{ $penjual }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
@endsection
