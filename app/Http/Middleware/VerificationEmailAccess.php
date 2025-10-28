<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificationEmailAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('web')->check()) {
            return to_route('login')->with('error', 'You must be logged in to access this page.');
        }

        if (auth('web')->user()->hasVerifiedEmail()) {
            return to_route('login')->with('error', 'Your account is already verified.');
        }

        return $next($request);
    }
}
