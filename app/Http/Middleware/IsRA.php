<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class IsRA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pre-Middleware Action
        if (Auth::check() && Auth::user()->role === "admin" || Auth::user()->role === "receptionist")
        {
            return $next($request);
        }
        return response('Unauthorized.', 401);
    }
}
