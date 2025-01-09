@extends('backend.layouts.email')

@section('title', 'Password Reset Request')

@section('content')
    <div class="header">
        <h1>Password Reset Request</h1>
    </div>

    <div class="content">
        <p>Hello,</p>
        <p>We received a request to reset your password. Please click the button below to reset your password:</p>
        <a href="{{ $resetLink }}" class="button">Reset Your Password</a>
        <p>If you did not request a password reset, please ignore this email or let us know if you have any concerns.</p>
    </div>

    <div class="note">
        <p><strong>Important:</strong> This link will expire in 60 minutes for your security.</p>
    </div>

    <div class="footer">
        <p>If you're having trouble clicking the button, copy and paste the following URL into your browser:</p>
        <p>{{ $resetLink }}</p>
        <p>Thanks,<br>{{ config('app.name') }} Team</p>
    </div>
@endsection
