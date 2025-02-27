@php
    $baseUrl = asset('frontend') . '/';
@endphp
@extends('frontend.layouts.app-login')

@section('title', 'Sign In')

@section('form')
    <div class="mainWhiteBox">
        <form method="POST" action="{{ route('login.post') }}" id="form_login">
            @csrf
            <div class="title">
                <h5>Sign In</h5>
                <p>Welcome! Please enter your details.</p>
            </div>
            <div class="form-group">
                <label class="labelClass" for="">Email</label>
                <input id="email" type="email" class="inputClass form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" autocomplete="email" autofocus
                    placeholder="Enter your email" id="emailaddress">
                @error('email')
                    <span class="error">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="labelClass" for="">Password</label>
                <div class="passwordShow">
                    <input type="password" name="password" id="password" class="inputClass password @error('email') is-invalid @enderror"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                    <a href="javascript:void(0)"><i class="bi bi-eye-slash togglePassword"></i></a>
                </div>
                @error('password')
                    <span class="error">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="checkReminder">
                <div class="clickTo">
                    <input type="checkbox" class="keyword-checkbox" id="checkbox-signin" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="" class="keyword-label">Remember login details</label>
                </div>
                <div class="forgot">
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                </div>
            </div>
            <div class="signIn">
                <button class="btn btnsignIn">Sign In</button>
            </div>
            <div class="divider"></div>
            <div class="googleBtn">
                <a class="google" href="{{ route('social', ['provider' => 'google']) }}"><img src="{{ $baseUrl }}assest/images/googleicon.png" alt="googleicon">Sign up with Google</a>
            </div>
        </form>
        <div class="account">
            <p>Didn't have an account? <a href="{{ route('register') }}">Sign Up</a></p>
        </div>
    </div>
@endsection
@section('js')
    <script>
        clearErrorOnInput('#form_login');
    </script>
@endsection

