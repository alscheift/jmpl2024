<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddLoginAttempts
{
    public function __invoke(Request $request, $next)
    {
        // Logic for adding login attempt
        
        $loginAttempts = session('loginAttempts', 0);
        session(['loginAttempts' => $loginAttempts + 1]);

        return $next($request);
    }
}
