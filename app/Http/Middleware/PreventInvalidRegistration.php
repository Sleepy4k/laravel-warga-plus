<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventInvalidRegistration
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

        $user = auth('web')->user();
        if (!$user->personal()->exists()) {
            return to_route('register.complete', [
                'payload' => encrypt($user->id . '|' . $user->phone)
            ]);
        }

        return $next($request);
    }
}
