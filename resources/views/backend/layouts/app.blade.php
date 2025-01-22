<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'Web') }}</title>
    <base href="/{{Request::segment(1)}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>

    <meta name="google-site-verification" content="">

    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="name" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" href="{{ asset('backend/images/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('backend/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('backend/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/images/favicon-32x32.png') }}">

    <!-- App css -->
    <link href="{{ asset('backend/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Datatables css -->
    <link href="{{ asset('backend/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/vendor/buttons.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/vendor/select.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/vendor/fixedHeader.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/vendor/fixedColumns.bootstrap5.css') }}" rel="stylesheet" type="text/css" />

    <!-- Quill css -->
    <link href="{{ asset('backend/css/vendor/quill.bubble.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('backend/css/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/jquery.toast.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/custom.css') }}?{{ cacheclear() }}" rel="stylesheet">

    @yield('css')
</head>

<body class="loading" data-layout-color="light" data-leftbar-theme="dark" data-layout-mode="fluid"
    data-rightbar-onstart="true">

    <!-- Pre-loader -->
    <div id="preloader">
        <div id="status">
            <div class="bouncing-loader">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <!-- End Preloader-->

    <!-- Begin page -->
    <div class="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
        @include('backend.layouts.sidebar')
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <div class="content">
                <!-- Topbar Start -->
                @include('backend.layouts.header')
                <!-- end Topbar -->

                <!-- Start Content-->
                <div class="container-fluid">
                    @yield('content')
                </div> <!-- container -->
            </div>
            <!-- Footer Start -->
            @include('backend.layouts.footer')
            <!-- end Footer -->
        </div><!-- content -->

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <div class="rightbar-overlay"></div>
    <!-- /End-bar -->

    <!-- bundle -->
    <script src="{{ asset('backend/js/vendor.min.js') }}"></script>
    <script src="{{ asset('backend/js/app.js') }}"></script>

    <!-- Datatables js -->
    <script src="{{ asset('backend/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/fixedColumns.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/fixedHeader.bootstrap5.min.js') }}"></script>

    <script src="{{ asset('backend/js/bootstrap-tagsinput.min.js') }}"></script>

    <!-- quill js -->
    <script src="{{ asset('backend/js/vendor/quill.min.js') }}"></script>
    <script src="{{ asset('backend/js/image-resize.min.js') }}"></script>

    <script src="{{ asset('backend/js/pages/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('backend/js/pages/sweetalert.min.js') }}"></script>
    <script src="{{ asset('backend/js/pages/custom.js') }}?{{ cacheclear() }}"></script>

    @if (Session::has('status'))
        <script type="text/javascript">
            showToastMessage("success", "{{ Session::get('status') }}");
        </script>
        @php Session::forget('status') @endphp
    @endif
    @if (Session::has('success'))
        <script type="text/javascript">
            showToastMessage("success", "{{ Session::get('success') }}");
        </script>
        @php Session::forget('success') @endphp
    @endif
        @if (Session::has('error'))
        <script type="text/javascript">
            showToastMessage("error", "{{ Session::get('error') }}");
        </script>
        @php Session::forget('error') @endphp
    @endif
    @if (Session::has('warning'))
        <script type="text/javascript">
            showToastMessage("warning", "{{ Session::get('warning') }}");
        </script>
        @php Session::forget('warning') @endphp
    @endif

    @yield('js')

    @yield('pagejs')
</body>

</html>