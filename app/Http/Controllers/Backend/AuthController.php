<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('backend.auth.login');
    }

    public function login(Request $request)
    {
        $messages = array(
            'email.email' => "The email must be a valid email address.",
            'email.regex' => "The email must be a valid email address.",
            'email.required' => "The email field is required.",
            'email.exists' => "This email does not exist.",
        );
        $this->validate($request, [
            'email' => "required|exists:users,email,status,1,role_type," . User::ADMIN,
            'password' => 'required',
        ], $messages);

        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->has('remember');

        $user = User::where('email', $email)
            ->where('status', User::ACTIVE)
            ->where('role_type', User::ADMIN)
            ->first();

        if (isset($user->id) && $user->id) {
            if (password_verify($password, $user->password)) {
                Auth::login($user, $remember);

                return redirect()->route("admin.dashboard");
            }
        }
        return redirect()->route("admin.login")->withInput()->withErrors(['email' => 'Oppes! You have entered invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
