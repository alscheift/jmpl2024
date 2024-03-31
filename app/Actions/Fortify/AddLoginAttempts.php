<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddLoginAttempts
{
    public function __invoke(Request $request, $next)
    {
        // Logic for adding login attempt
        return $next($request);
    }
}
