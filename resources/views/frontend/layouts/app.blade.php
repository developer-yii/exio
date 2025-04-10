@php
    $baseUrl = asset('frontend') . '/';
    $routeName = request()->route()->getName();
    $showCityDropdown = in_array($routeName, ['property.result.filter', 'front.home']);
    $bodyClass = $routeName == 'property.result.filter' ? 'matchProperty' : '';
    $headerClass = $routeName == 'property.details' ? 'removeHeader headerInner' : '';
    $cityDropDownClass = in_array($routeName, ['property.result.filter', 'front.home']) ? '' : 'mobileHide';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'Web') }}</title>
    <base href="/{{ Request::segment(1) }}">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <meta name="google-site-verification" content="">

    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="name" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" type="image/x-icon" href="{{ $baseUrl }}assest/images/x-icon.png">
    @yield('meta_details')

    <!-- Open Graph Metadata -->
    <meta property="og:title" content="@yield('og_title')">
    <meta property="og:description" content="@yield('og_description')">
    <meta property="og:image" content="@yield('og_image')">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="628">
    <meta property="og:url" content="@yield('og_url')">
    <meta property="og:type" content="@yield('og_type', 'website')">

    <!-- Twitter Card Metadata -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title')">
    <meta name="twitter:description" content="@yield('og_description')">
    <meta name="twitter:image" content="@yield('og_image')">

    <!-- App css -->
    <link rel="stylesheet" href="{{ $baseUrl }}assest/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="{{ $baseUrl }}assest/css/style.css" />
    <link rel="stylesheet" href="{{ $baseUrl }}assest/css/responsive.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
    <link rel="stylesheet" href="{{ $baseUrl }}assest/css/custom.css" />

    {{-- Extra css --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @yield('css')
</head>

<body class="{{ $bodyClass }}">
    <div class="siteLoader" id="siteLoader" style="display: none;">
        <section class="loaderSec">
            <span class="loader-11"></span>
        </section>
    </div>
    <!-- header start -->
    @include('frontend.layouts.header', ['showCityDropdown' => $showCityDropdown])
    <!-- header end -->

    @yield('content')

    <!-- footer start -->
    @php
        $dontShowFooter = in_array(request()->route()->getName(), [
            'front.check-and-match-property.result',
            'front.check-and-match-property',
            'property.result.filter'
        ]);
    @endphp

    @include('frontend.layouts.footer', ['footer_display' => $dontShowFooter ? 'd-none' : ''])
    <!-- footer end -->

    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">

    <script src="{{ $baseUrl }}assest/js/jquery-3.7.1.min.js"></script>
    <script src="{{ $baseUrl }}assest/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="{{ $baseUrl }}assest/js/custom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-elevatezoom/3.0.8/jquery.elevatezoom.min.js"></script>

    <script type="text/javascript">
        var isUserLoggedIn = @json(Auth::check());
    </script>

    {{-- Extra Js --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>

    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    {{-- <script src="assest/js/custom.js"></script> --}}

    <script>
        var deviceType = "{{ getDeviceType() }}";
        var propertyLikeUrl = "{{ route('property.like-unlike') }}";
        var subscribeUrl = "{{ route('subscribe') }}";
        var downloadInsightsRrportUrl = "{{ route('property.download-insights-report') }}";
        var getPropertyByCityUrl = "{{ route('front.home.getProjects') }}";
        var authId = "{{ auth()->user()->id ?? '' }}";
        
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

    <script src="{{ frontendPageJsLink('custom.js') }}"></script>
        
    @yield('modal')
    @yield('js')
    @if(session('download-success'))
    <script>
        // Wait for page load
        window.addEventListener('DOMContentLoaded', function () {
            toastr.success("{{ session('download-success') }}");

            // Trigger download using a hidden iframe
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = "{{ route('download.compare.report') }}";
            document.body.appendChild(iframe);
        });
    </script>
@endif

    <!-- @if(session('download-success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {               
                toastr.success("{{ session('download-success') }}");

                // Start the download automatically
                window.location.href = "{{ url('/download-compare-report') }}";
                // setTimeout(() => {
                //     window.location.href = "{{ url('/download-compare-report') }}";
                // }, 1500);
            });
        </script>
        @php Session::forget('download-success') @endphp
    @endif -->

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
