@extends('backend.layouts.login')

@section('title', 'Forgot Password')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-4 col-lg-5">
            <div class="card">

                <div class="card-header pt-4 pb-4 text-center">
                    <a href="#">
                        <span><img src="{{ asset('backend/images/logo.png') }}" alt="" height="100"></span>
                    </a>
                </div>

                <div class="card-body p-4">

                    <div class="text-center w-75 m-auto">
                        <h4 class="text-dark-50 text-center pb-0 fw-bold">Forgot Password</h4>
                        <p class="text-muted mb-4">Enter your email and we'll send you a link to get back into your account.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.password.forgot.post') }}" id="form_forgot">
                        @csrf

                        <div class="mb-3">
                            <label for="emailaddress" class="form-label">Email address</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" autocomplete="email" autofocus
                                placeholder="Enter your email" id="emailaddress">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 mb-0 text-center">
                            <button class="btn btn-primary" type="submit">Send Password Reset Link</button>
                        </div>
                    </form>
                </div> <!-- end card-body -->
            </div>
            <!-- end card -->

            <div class="row mt-3">
                <div class="col-12 text-center">
                    <p class="text-muted">Back to <a href="{{ route('admin.login') }}" class="text-muted ms-1"><b>Log In</b></a></p>
                </div> <!-- end col -->
            </div>

        </div> <!-- end col -->
    </div>
@endsection

@section('pagejs')
    <script>
        $(document).ready(function() {
            $('#form_forgot').on('keyup change', 'input, textarea, select', function(event) {
                if ($.trim($(this).val()) && $(this).val().length > 0) {
                    $(this).removeClass('is-invalid')
                    $(this).closest('.mb-3').find('.invalid-feedback strong').html('');
                } else {
                    $(this).removeClass('is-valid');
                }
            });
        });
    </script>
@endsection
