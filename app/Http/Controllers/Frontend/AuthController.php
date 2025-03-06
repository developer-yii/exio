<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate user input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:15',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => 'required|string|min:8',
            'terms' => 'accepted',
        ]);

        // Generate a verification token
        $token = Str::random(64);

        // Create the user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'mobile' => $validatedData['mobile_number'],
            'password' => Hash::make($validatedData['password']),
            'remember_token' => $token,
        ]);

        // Send verification email if user is created successfully
        if ($user->exists) {
            try {
                Mail::to($user->email)->send(new EmailVerificationMail($user));

                return redirect()->route('register')->with('success', 'A verification link has been send to '.$user->email.'.
                        Please check an email and click on the included link to verify your email. Don’t receive the email? <br> <button class="btn btn-primary resendlink" data-email="'.$user->email.'">Click to resend</button>');

            } catch (\Exception $e) {
                // \Log::error("Email sending failed: " . $e->getMessage());
                session()->flash('error', "Registration successful, but we couldn't send the verification email. Please contact support.");
            }
        }

        return redirect()->route('login');
    }

    public function verifyAccount($token)
    {
        // Find user by token
        $verifyUser = User::where('remember_token', $token)->first();

        if (!$verifyUser) {
            return redirect()->route('login')->with('error', 'Sorry, your email cannot be identified.');
        }

        if ($verifyUser->email_verified_at) {
            session()->flash('warning', 'Your email is already verified.');
            return redirect()->intended();
        }

        $verifyUser->email_verified_at = Carbon::now();
        // $verifyUser->remember_token = null; // Clear token after verification

        if ($verifyUser->save()) {
            session()->flash('success', 'Your e-mail has been verified successfully.');

            // Log in the user
            Auth::login($verifyUser);
            return redirect()->intended();
        }

        return redirect()->route('login')->with('error', 'Something went wrong. Please try again.');
    }

    public function resendVerificationMail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if(isset($user->id) && $user->id != Null){

            Mail::to($user->email)->send(new EmailVerificationMail($user));

            $result = ['status' => true, 'message' => "email send successfully", 'email' => $request->email];
            return response()->json($result);
        }
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
            'email' => "required|exists:users,email",
            'password' => 'required',
        ], $messages);

        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->has('remember');
        // Config::set('session.expire_on_close', !$remember);

        $user = User::where('email', $email)->first();
        if(!$user->email_verified_at){
            return redirect()->route('login')->with('error', 'Please verify email first.');
        }

        if($user->status != User::ACTIVE){
            return redirect()->route('login')->with('error', 'Your account is inactive.');
        }

        if (isset($user->id) && $user->id) {
            if (password_verify($password, $user->password)) {
                Auth::login($user, $remember);
                session()->flash('success', "Your account login successfully");
                return redirect()->intended();
            }
        }
        return redirect()->route("login")->withInput()->withErrors(['email' => 'Oppes! You have entered invalid credentials']);
    }
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->flush();
            Session::forget('url.intended');
            Auth::logout();

            session()->flash('success', 'You are successfully logout.');
            return redirect('/');
        }
        return redirect('/');
    }

}
