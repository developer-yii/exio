<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    // protected function redirectTo()
    // {
    //     return '/admin/home';
    // }
    public function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ]);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $get_user = User::where('email', $request->email)->first();
        $credentials['email'] = $request->email;
        $credentials['password'] = $request->password;
        if (isset($get_user) && $get_user != NULL) {

            if (($get_user->user_type == '1') || ($get_user->user_type == '2')) {
                if (Auth::attempt($credentials)) {
                    return redirect()->intended(route('admin.home'));
                } else {
                    return redirect('login')->withErrors(['password' => 'The Password is wrong.'])->withInput();
                }
            }
            if ($get_user->user_type == '3' && $get_user->is_active == '1' && $get_user->email_verified_at != null) {
                if (Auth::attempt($credentials)){
                    return redirect()->intended(route('customer.home'));
                } else {
                    return redirect('login')->withErrors(['password' => 'The Password is wrong.'])->withInput();
                }
            }
            if ($get_user->user_type == '3' && $get_user->email_verified_at == null) {
                return redirect('login')->withErrors(['approve' => 'Please verify email first.'])->withInput();
            }
            if ($get_user->user_type == '3' && $get_user->email_verified_at != null && $get_user->is_active == '0') {
                return redirect('login')->withErrors(['approve' => 'Your account is not activated'])->withInput();
            }
        } else {
            return redirect('login')->withErrors(['approve' => 'Provided credential does not match in our records.'])->withInput();
        }
    }

    public function logout(Request $request)
    {
        // Ensure the user is authenticated before attempting to logout
        if (Auth::check()) {
            // Invalidate the session
            $request->session()->invalidate();

            // Regenerate the CSRF token
            $request->session()->regenerateToken();

            // Flush all session data
            $request->session()->flush();

            // Clear intended URL
            Session::forget('url.intended');

            // Perform the logout
            Auth::logout();

            // Redirect to login with a success message
            return redirect('/');
        }
        // Redirect to login if the user is not authenticated
        return redirect('/');
    }

}
