<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
// use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('frontend.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $rules = [
            'user_name' => 'required|string|max:100',   
            'user_password' => 'nullable|min:8|max:16',                       
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
        
        $userId = auth()->id();
        $model = User::find($userId);

        if (!$model) {
            return response()->json(['status' => false, 'message' => 'User not found']);
        }

        $model->name = $request->user_name;
        $model->mobile =  $request->filled('user_mobile') ? str_replace(' ', '', trim($request->user_mobile)) : null;
        if($request->filled('user_password')){
            $model->password = Hash::make($request->user_password);
        }

        if ($model->save()) {
            return response()->json(['status' => true, 'message' => 'Profile successfully updated']);
        }

        return response()->json(['status' => false, 'message' => 'Error saving profile data']);

       
    }    
}
