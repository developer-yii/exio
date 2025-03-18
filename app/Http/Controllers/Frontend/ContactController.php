<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormEmail;
use App\Models\Contact;
use App\Rules\ReCaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index(){
        return view('frontend.pages.contact-us');
    }

    public function submit(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required',
            'message' => 'required',
            'g-recaptcha-response' => ['required', new ReCaptcha],
        ]);

        if ($validatedData->fails())
        {
            $response = ['status' => false,'errors' => $validatedData->errors()];
            return response()->json($response);
        }

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->mobile_number = $request->mobile_number;
        $contact->message = $request->message;
        if($contact->save())
        {
           $data = [
                'name' => $request->name,
                'email' => $request->email,
                'mobile_number' => $request->mobile_number,
                'message' => $request->message,
            ];

            Mail::to($request->email)->send(new ContactFormEmail($data));

            $response = ['status' => true,'message' => "Email Send Successfully" ];
        }
        else
        {
            $response = ['status' => false,'message' => "Email Not Sent" ];
        }
        return response()->json($response);
    }
}
