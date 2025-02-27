@extends('frontend.layouts.app-login')

@section('title', 'Reset Password')

@section('form')
<div class="mainWhiteBox">
    <form id="passwordresetform" action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="title">
            <h5>Recover Password</h5>
        </div>
        <div class="form-group">
            <label class="labelClass" for="">Email</label>
            <input id="email" type="email" class="inputClass form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" readonly>
            @error('email')
                <span class="error">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label class="labelClass" for="">New Password</label>
            <div class="passwordShow">
                <input class="inputClass password @error('password') is-invalid @enderror" type="password" id="password" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" autocomplete="new-password">
                <a href="javascript:void(0)"><i class="bi bi-eye-slash togglePassword"></i></a>
            </div>
            @error('password')
                <span class="error">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label class="labelClass" for="">Confirm New Password</label>
            <div class="passwordShow">
                <input class="inputClass password @error('password') is-invalid @enderror" type="password" id="password-confirm" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" autocomplete="new-password">
                <a href="javascript:void(0)"><i class="bi bi-eye-slash togglePassword"></i></a>
            </div>
            @error('password')
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
        <p>Remember Password? <a href="sign_in.html">Sign In</a></p>
    </div>
</div>
@endsection
@section('js')
    <script>
        clearErrorOnInput('#passwordresetform');
    </script>
@endsection