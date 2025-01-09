<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" href="{{ asset('backend/images/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('backend/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('backend/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/images/favicon-32x32.png') }}">

    <!-- App css -->
    <link href="{{ asset('backend/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <link href="{{ asset('backend/css/jquery.toast.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/custom.css') }}?{{ cacheclear() }}" rel="stylesheet">

    @yield('css')

</head>

<body class="loading" data-layout-config='{"darkMode":false}'>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            @yield('content')
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        © {{ date('Y') }} {{ config('app.name', 'App') }}. All Rights Reserved.
    </footer>

    <!-- bundle -->
    <script src="{{ asset('backend/js/vendor.min.js') }}"></script>
    <script src="{{ asset('backend/js/app.min.js') }}"></script>

    <script src="{{ asset('backend/js/pages/jquery.toast.min.js') }}"></script>
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
