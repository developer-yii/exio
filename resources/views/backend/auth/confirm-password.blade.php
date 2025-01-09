@extends('backend.layouts.login')

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
                        <h4 class="text-dark-50 text-center pb-0 fw-bold">
                            Confirm Password
                        </h4>
                        <p class="text-muted mb-4">{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</p>
                    </div>

                    <form method="POST" action="{{ route('admin.password.confirm.post') }}" id="form_confirm">
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group input-group-merge @error('password') is-invalid @enderror">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password">
                                <div class="input-group-text" data-password="false">
                                    <span class="password-eye"></span>
                                </div>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 mb-0 text-center">
                            <button class="btn btn-primary" type="submit">Confirm</button>
                        </div>
                    </form>
                </div> <!-- end card-body -->
            </div>
            <!-- end card -->

        </div> <!-- end col -->
    </div>
@endsection

@section('pagejs')
    <script>
        $(document).ready(function() {
            $('#form_confirm').on('keyup change', 'input, textarea, select', function(event) {
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
