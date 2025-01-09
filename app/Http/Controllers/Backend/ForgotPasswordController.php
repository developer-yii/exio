<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('backend.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'exists:users,email,status,1,role_type,' . User::ADMIN,
            ],
        ], [
            'email.exists' => __('The email must belong to an active admin user.')
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        $resetLink = route('admin.password.reset', ['token' => $token, 'email' => $request->email]);

        Mail::send('backend.email.forget-password', ['resetLink' => $resetLink], function ($message) use ($request) {
            $message->to($request->email)->subject(__('Reset Your Password'));
        });

        return back()->with('success', __('A password reset link has been sent to your email.'));
    }

    public function showResetForm($token, $email)
    {
        return view('backend.auth.reset-password', compact('token', 'email'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'exists:users,email,status,1,role_type,' . User::ADMIN,
            ],
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.exists' => __('The email must belong to an active admin user.'),
        ]);

        $passwordResetEntry = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordResetEntry) {
            return back()->withInput()->withErrors([
                'token' => __('The reset token is invalid or has expired.')
            ]);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('admin.login')->with('success', __('Your password has been reset successfully.'));
    }
}
