<!DOCTYPE html>
<html>

@include('includes.head')

<body class="skin-1">

    <div id="wrapper">
        @if (Auth::user())
            @include('includes.sidebar')
        @endif

        <div id="page-wrapper" class="gray-bg">
            @include('includes.navbar')
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-lg-12">
                        @yield('content')
                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="pull-right">
                    {{-- <a href="https://api.whatsapp.com/send?phone=+6283895627355&text=Hallo Mengingatkan kembali untuk tagihan bernama Dadang untuk segera diselesaikan" target="_blank" class="">Whatsapp</a> --}}
                </div>
                <div>
                    <strong>Copyright</strong> Vintage  &copy; 2023
                </div>
            </div>

        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{ asset('registered/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('registered/js/popper.min.js') }}"></script>
    <script src="{{ asset('registered/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('registered/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('registered/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom and plugin javascript -->
    <script src="{{ asset('registered/js/inspinia.js') }}"></script>
    <script src="{{ asset('registered/js/plugins/pace/pace.min.js') }}"></script>
    @yield('js')

</body>

</html>
