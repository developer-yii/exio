<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Exception;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    public function callback($provider)
    {
        try
        {
            $social_user = Socialite::driver($provider)->user();

            if(isset($social_user->id))
            {
                $user = User::where('provider_id', $social_user->id)->first();

                if(empty($user)){
                    $user = User::where('email',$social_user->email)->first();
                }

                if (isset($user->id)){
                    $user->provider= $provider;
                    $user->provider_id= $social_user->id;
                    $user->email_verified_at= Carbon::now();
                    if($user->save()){
                        Auth::login($user);
                        session()->flash('success', "Your account login successfully");
                        return redirect()->route('front.home');
                    }
                }else{
                    $user = new User;
                    $user->first_name = $social_user->name;
                    $user->email = $social_user->email!='' ? $social_user->email : NULL;
                    $user->password = Hash::make($social_user->id);
                    $user->provider = $provider;
                    $user->provider_id = $social_user->id;
                    $user->created_at = Carbon::now();
                    if($user->save()){
                        Auth::login($user);
                        session()->flash('success', "Your account login successfully");
                        return redirect()->route('front.home');
                    }
                }
            }
            return redirect()->route('login');
        }
        catch (Exception $e)
        {
            dd($e->getMessage());
        }
    }
}
