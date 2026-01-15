<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements ValidationRule
{
    public $recaptchaResponse;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($recaptchaResponse)
    {
        $this->recaptchaResponse = $recaptchaResponse;
    }

    function get_recaptcha_response() {
        return $this->recaptchaResponse;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $recaptcha_response = $this->get_recaptcha_response();
        $response = Http::withOptions([
            'verify' => false,
        ])->asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
                'response' => $recaptcha_response
            ]
        );

        if (!json_decode($response->body(), true)['success']) {
            $fail('El campo reCAPTCHA es invalido.');
        }

        /*$response = Http::withOptions([
            'verify' => false,
        ])->get("https://www.google.com/recaptcha/api/siteverify",[
                'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
                'response' => $recaptcha_response
            ]);

        if($response['success'] == false){
            $fail('El campo reCAPTCHA es invalido.');
        }*/
          
    }
}
