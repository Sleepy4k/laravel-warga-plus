<?php

namespace App\Services\Web\Auth;

use App\Foundations\Service;
use App\Support\AttributeEncryptor;
use Illuminate\Support\Facades\Auth;
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
        $identifier = (string) trim($request['phone-identity'] ?? '');
        $password = $request['password'] ?? '';

        $phonePattern = '/^8[1-9][0-9]{6,15}$/';
        $isPhone = preg_match($phonePattern, $identifier) === 1;

        $key = $isPhone ? 'phone' : 'identity_number';

        $payload = [
            $key => AttributeEncryptor::encrypt($identifier),
            'password' => $password,
        ];

        if (!Auth::attempt($payload)) {
            return null;
        }

        $user = auth('web')->user();

        if (!$user?->is_active && !$user->hasRole('user')) {
            $user->deactivate();
        }

        RateLimiter::clear('login-'.request()->ip());
        session()->regenerate();

        return $user;
    }
}
