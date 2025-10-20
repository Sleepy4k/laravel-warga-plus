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
        $identifier = $request['phone-identity'];
        $phonePattern = '/^(\+62|62|0)8[1-9][0-9]{6,10}$/';
        $payload = [
            'password' => $request['password'],
            preg_match($phonePattern, $identifier) ? 'phone' : 'identity_number' => $identifier,
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
