<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CaptchaValidation
{
    public function __invoke(Request $request, $next)
    {
        // Validator::make($request->all(), [
        //     'recaptcha_token' => ['required'],
        // ])->validate();

        return $next($request);
    }
}
