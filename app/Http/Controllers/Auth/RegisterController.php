<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\Models\PaymentMethod;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UserPaymentMethod;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required'],
            'mobile_number' => ['required'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    public function resendVerificationMail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if(isset($user->id) && $user->id != Null){
            Mail::send('emails.emailVerification', ['user' => $user], function($message) use($user){
                $message->to($user->email);
                $message->subject('Email Verification Mail');
            });

            $result = ['status' => true, 'message' => "email send successfully", 'email' => $request->email];
            return response()->json($result);
        }
    }
    protected function create(array $data)
    {
        $token = Str::random(64);
        $user =  User::create([
            'first_name' => $data['first_name'],
            'email' => $data['email'],
            'mobile' => $data['mobile_number'],
            'password' => Hash::make($data['password']),
            'remember_token' => $token,
        ]);

        if(isset($user->id) && $user->id != Null){
            Mail::send('emails.emailVerification', ['user' => $user], function($message) use($user){
                $message->to($user->email);
                $message->subject('Email Verification Mail');
            });

            return $user;
        }
    }
    public function register(Request $request)
    {

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        // return redirect()->route('register')->with('success', 'A verification link has been send to '.$user->email.'.
        //                 Please check an email and click on the included link to verify your email. Don’t receive the email? <button class="btn btn-custom linkGoto resendlink" data-email="'.$user->email.'">Click to resend</button>');

    }

    public function verifyAccount($token)
    {
        $verifyUser = User::where('remember_token', $token)->first();
        if (!is_null($verifyUser))
        {
            if (!$verifyUser->email_verified_at){
                $verifyUser->email_verified_at = Carbon::now();

                if ($verifyUser->save()){
                    $users = User::where('user_type', 1)->get();
                    foreach($users as $user){
                        Mail::send('emails.accountActivationEmail', ['user' => $verifyUser], function($message) use($user){
                            $message->to($user->email);
                            $message->subject('Account Activation Mail');
                        });
                    }

                    return redirect()->route('login')->with('success', 'Your e-mail is verified successfully. you will get mail when your account is activated.');

                }else{
                    // Session::flash('verify', 'Something went wrong.');
                    return redirect()->route('login')->with('error', 'Something went wrong.');
                }
            }else{
                // Session::flash('verify', 'Your e-mail is already verified.');
                return redirect()->route('login')->with('warning', 'Your e-mail is already verified.');
                // return view("messages");
            }
        }
        // Session::flash('verify', 'Sorry your email cannot be identified.');
        return redirect()->route('login')->with('error', 'Sorry your email cannot be identified.');
        // return view("messages");
    }
}
