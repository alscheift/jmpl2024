<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResetLoginAttempts
{
    public function __invoke(Request $request, $next)
    {
        // Logic for resetting login attempt
        return $next($request);
    }
}
