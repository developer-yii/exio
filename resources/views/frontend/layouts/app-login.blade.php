@php
    $baseUrl = asset('frontend').'/';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" href="{{$baseUrl}}assest/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/x-icon" href="{{$baseUrl}}assest/images/x-icon.png">
    <link rel="stylesheet" href="{{$baseUrl}}assest/css/style.css" />
    <link rel="stylesheet" href="{{$baseUrl}}assest/css/responsive.css" />
    <link rel="stylesheet" href="{{$baseUrl}}assest/css/custom.css" />

     {{-- Extra css --}}
     <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body>

    <!-- sign in section -->
    <section class="sign_in">
        <div class="container">
            <div class="sign_in_box">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="leftBox">
                            <div class="logoBox">
                                <a href="index.html"><img src="{{ $baseUrl }}assest/images/logo-img.png" alt="logo-img" /></a>
                            </div>
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {!! session('success') !!}
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {!! session('error') !!}
                                </div>
                            @endif
                            @yield('form')
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="rightBox">
                            <img src="{{$baseUrl}}assest/images/login-img.png" alt="login-img">
                            <div class="textBox">
                                <h5>About Exio</h5>
                                <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- sign in section -->

    <script src="{{$baseUrl}}assest/js/jquery-3.7.1.min.js"></script>
    <script src="{{$baseUrl}}assest/js/bootstrap.bundle.min.js"></script>
    <script src="{{$baseUrl}}assest/js/custom.js"></script>
    <script src="{{ frontendPageJsLink('custom.js') }}"></script>

    {{-- Extra Js --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @yield('js')
</body>
</html>