@php
    $baseUrl = asset('frontend') . '/';
@endphp
@extends('frontend.layouts.app-login')

@section('title', 'Reset Password')

@section('form')
    <div class="col-md-6">
        <div class="leftBox">
            <div class="logoBox">
                <a href="index.html"><img src="{{$baseUrl}}assest/mages/logo-img.png" alt="logo-img" /></a>
            </div>
            <div class="mainWhiteBox">
                <form action="">
                    <div class="title">
                        <h5>Reset Password</h5>
                        <p>Enter your email address associated with your account and we’ll send you a link to reset your
                            password.</p>
                    </div>
                    <div class="form-group">
                        <label class="labelClass" for="">New Password</label>
                        <div class="passwordShow">
                            <input class="inputClass password" type="password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                            <a href="javascript:void(0)"><i class="bi bi-eye-slash togglePassword"></i></a>
                        </div>
                        <span class="error">Please Enter vaild Password</span>
                    </div>
                    <div class="form-group">
                        <label class="labelClass" for="">Confirm New Password</label>
                        <div class="passwordShow">
                            <input class="inputClass password" type="password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                            <a href="javascript:void(0)"><i class="bi bi-eye-slash togglePassword"></i></a>
                        </div>
                    </div>
                    <div class="signIn">
                        <button class="btn btnsignIn">Continue</button>
                    </div>
                </form>
                <div class="account">
                    <p>Remember Password? <a href="sign_in.html">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
