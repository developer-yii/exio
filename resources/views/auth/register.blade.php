@php
    $baseUrl = asset('frontend') . '/';
@endphp
@extends('frontend.layouts.app-login')

@section('title', 'Sign Up')

@section('form')
    <div class="mainWhiteBox">
        <form id="registerform" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="title">
                <h5>Sign Up</h5>
                <p>Create Your Account.</p>
            </div>
            <div class="form-group">
                <label class="labelClass" for="">Name</label>
                <input class="inputClass form-control @error('name') is-invalid @enderror" type="text" placeholder="Enter your Name" name='name' id="name" value="{{ old('name') }}">
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="labelClass" for="">Email</label>
                <input class="inputClass form-control @error('email') is-invalid @enderror" type="text" placeholder="Enter your Email" name="email" id="email"
                    value="{{ old('email') }}">
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="labelClass" for="">Mobile number</label>
                <input class="inputClass form-control @error('email') is-invalid @enderror" type="text" placeholder="Enter your mobile number" name="mobile_number"
                    id="mobile_number" value="{{ old('mobile_number') }}">
                @error('mobile_number')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="labelClass" for="">Password</label>
                <div class="passwordShow">
                    <input class="inputClass password @error('email') is-invalid @enderror" type="password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        name="password" id="password">
                    <a href="javascript:void(0)"><i class="bi bi-eye-slash togglePassword"></i></a>
                </div>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="checkReminder">
                <div class="clickTo">
                    <input type="checkbox" class="keyword-checkbox" name="terms" id="terms">
                    <label for="" class="keyword-label">By clicking, I accept Exio’s
                        <a href="{{route('terms-condition')}}">terms of use.</a>
                    </label>
                    @error('terms')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="signIn">
                <button class="btn btnsignIn">Sign Up</button>
            </div>
        </form>
        <div class="account">
            <p>Already have an account? <a href="{{route('login')}}">Sign in</a></p>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var resendVerificationEmailUrl = "{{ route('resendverificationmail') }}";
        $('.resendlink').on('click', function(event) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            event.preventDefault();
            var $this = $(this);

            var userEmail = $this.data('email');

            $.ajax({
                type: 'POST',
                url: resendVerificationEmailUrl,
                data: { email: userEmail },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                beforeSend: function(){
                    $this.prop('disabled', true);
                },
                success: function(result){
                    toastr.success(result.message);
                },
                error: function(result){
                    alert('Error: ' + result.responseText);
                },
                complete: function() {
                    $this.prop('disabled', false);
                }
            });
        });

        clearErrorOnInput('#registerform');
    </script>
@endsection
