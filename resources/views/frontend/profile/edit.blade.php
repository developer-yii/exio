@php
    $baseUrl = asset('frontend') . '/';
    $metaTitle = "Exio | Edit Profile";
    $metaDesc = "Exio | Edit Profile";
@endphp
@extends('frontend.layouts.app')

@section('title', 'Edit Profile')
@section('og_title', $metaTitle)
@section('og_description', $metaDesc)
@section('og_url', url()->current())

@section('content')
    <section class="edit_profile">
        <div class="edit_profile_box">
            <div class="mainWhiteBox">
                <form method="POST" id="updateProfile" action="#">
                    @csrf
                    <div class="title">
                        <h5>Edit Profile</h5>
                    </div>
                    <div class="form-group">
                        <label class="labelClass" for="">Email</label>
                        <input class="inputClass" type="email" name="user_email" value="{{ $user->email }}" id="user_email" placeholder="Enter your Email" readonly disabled>
                        <span class="error"></span>
                    </div>
                    <div class="form-group">
                        <label class="labelClass" for="">Name*</label>
                        <input class="inputClass" type="text" name="user_name" value="{{ $user->name }}" id="user_name" placeholder="Enter your name">
                        <span class="error"></span>
                    </div>
                   
                    <div class="form-group">
                        <label class="labelClass" for="">Mobile Number</label>
                        <input class="inputClass" type="text" name="user_mobile" value="{{ $user->mobile }}" id="user_mobile" placeholder="Enter your mobile number">
                    </div>
                    <div class="form-group">
                        <label class="labelClass" for="">Update Password</label>
                        <div class="passwordShow">
                            <input class="inputClass password" type="password" name="user_password" id="user_password" placeholder="············">
                            <a href="javascript:void(0)"><i class="bi bi-eye-slash togglePassword"></i></a>
                        </div>
                        <span class="error"></span>
                    </div>
                    <div class="signIn">
                        <button class="btn btnsignIn" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>        
        var updateProfileUrl = "{{ route('profile.update') }}";
    </script>
    <script src="{{ frontendPageJsLink('profile.js') }}"></script>    
@endsection


