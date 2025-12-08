<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FrontendAuth
{
public function handle($request, Closure $next)
{
    if (!session()->has('user')) {
        return redirect()->route('login.simulasi');
    }

    return $next($request);
}

}
