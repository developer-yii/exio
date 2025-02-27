@php
    $baseUrl = asset('frontend').'/';
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'Web') }}</title>
    <base href="/{{Request::segment(1)}}">

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

    <link rel="icon" type="image/x-icon" href="{{$baseUrl}}assest/images/x-icon.png">

    <!-- App css -->
    <link rel="stylesheet" href="{{$baseUrl}}assest/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/x-icon" href="{{$baseUrl}}assest/images/x-icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
    <link rel="stylesheet" href="{{$baseUrl}}assest/css/style.css" />
    <link rel="stylesheet" href="{{$baseUrl}}assest/css/responsive.css" />

    {{-- Extra css --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @yield('css')
</head>

<body class="block_model">
    <!-- header start -->
    @include('frontend.layouts.header')
    <!-- header end -->

        @yield('content')

    <!-- footer start -->
    @include('frontend.layouts.footer')
    <!-- footer end -->

    <script src="{{$baseUrl}}assest/js/jquery-3.7.1.min.js"></script>
    <script src="{{$baseUrl}}assest/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="{{$baseUrl}}assest/js/custom.js"></script>

    {{-- Extra Js --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

    @yield('js')
    @if (session('success'))
        <script type="text/javascript">
            toastr.success("{{ session('success') }}");
        </script>
        @php Session::forget('success') @endphp
    @endif
    @if (Session::has('error'))
        <script type="text/javascript">
            toastr.error("{{ session('error') }}");
        </script>
        @php Session::forget('error') @endphp
    @endif
    @if (session('warning'))
        <script type="text/javascript">
            toastr.warning("{{ session('warning') }}");
        </script>
        @php Session::forget('warning') @endphp
    @endif

  </body>

</html>