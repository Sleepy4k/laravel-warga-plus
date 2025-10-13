<?php

namespace App\Services\Web\Auth;

use App\Foundations\Service;
use Illuminate\Support\Facades\RateLimiter;

class LoginService extends Service
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $userIp = request()->ip();
        $key = 'login-' . $userIp;
        $rateLimiter = [
            'reset_at' => RateLimiter::availableIn($key),
            'remaining' => RateLimiter::remaining($key, config('auth.defaults.max_attempts')),
        ];

        return compact('rateLimiter');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     *
     * @return mixed
     */
    public function store(array $request): mixed
    {
        $identifier = $request['email-username'];
        $payload = [
            'password' => $request['password'],
            filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $identifier,
        ];

        $attempt = auth('web')->attempt($payload);
        if (!$attempt) return null;

        $user = auth('web')->user();

        if (!$user?->is_active && !$user->hasRole('user')) {
            $user->deactivate();
        }

        RateLimiter::clear('login-'.request()->ip());
        session()->regenerate();

        return $user;
    }
}
