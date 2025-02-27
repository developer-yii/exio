@extends('frontend.layouts.app-login')

@section('form')
    <div class="mainWhiteBox">
        <form id="forgotpasswordform" action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="title">
                <h5>Forget Password</h5>
                <p>Enter your email address associated with your account and we’ll send you a link to reset your password.
                </p>
            </div>
            <div class="form-group">
                <label class="labelClass" for="">Email</label>
                <input id="email" type="email" class="inputClass form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" autocomplete="email" placeholder="Enter your Email" autofocus>
                @error('email')
                    <span class="error">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="signIn">
                <button class="btn btnsignIn">Continue</button>
            </div>
        </form>
        <div class="account">
            <p>Remember Password? <a href="{{ route('login') }}">Sign In</a></p>
        </div>
    </div>
@endsection
@section('js')
    <script>
        clearErrorOnInput('#forgotpasswordform');
    </script>
@endsection
