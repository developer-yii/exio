<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements Rule
{
    public function passes($attribute, $value)
    {
        $secretKey = env('GOOGLE_RECAPTCHA_SECRET');
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => $secretKey,
            'response' => $value,
        ]);

        $responseData = $response->json();

        return $responseData['success'] ?? false;
    }

    public function message()
    {
        return 'Please complete the reCAPTCHA verification.';
    }
}
