<?php

namespace App\Actions\Fortify;

use App\Rules\Recaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CaptchaValidation
{
    public function __invoke(Request $request, $next)
    {

        // if login attempts exceed 3, validate the recaptcha token
        if (session('loginAttempts', 0) >= 3) {
            Validator::make($request->all(), [
                'recaptcha_token' => ['required', new Recaptcha()],
            ])->validate();
        }

        return $next($request);
    }
}
