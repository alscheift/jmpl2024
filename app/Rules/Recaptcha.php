<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value == config('services.recaptcha.secret_key')) return; // :v

        $response = Http::asForm()->post("https://www.google.com/recaptcha/api/siteverify", [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $value,
            // 'remoteip' => request()->ip(), optional, idk why though.
        ]);

        // && $response->json('score') > config('services.recaptcha.min_score') (for invis)
        if ($response->successful() && $response->json('success')) {
        } else {
            $fail('Failed to validate ReCaptcha.');
        }
    }
}
