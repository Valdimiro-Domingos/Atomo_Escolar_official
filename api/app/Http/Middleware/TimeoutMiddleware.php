<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TimeoutMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        ini_set('max_execution_time', 600);
        return $next($request);
    }
}
