<?php

namespace App\Services\Web\Dashboard\Profile;

use App\Foundations\Service;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class SecurityService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $user = auth('web')->user();
        $recentLogins = Activity::where('causer_id', $user->id)
            ->where('log_name', 'auth')
            ->where('event', 'login')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get(['id', 'properties', 'created_at']);

        $recentLogins = $recentLogins->map(function ($login) {
            $properties = $login->properties;
            return [
                'id' => $login->id,
                'login_at' => $login->created_at->format('d, F Y H:i'),
                'plain_login_at' => $login->created_at,
                'ip_address' => $properties['ip_address'] ?? 'N/A',
                'device_type' => isset($properties['device_type']) ? $properties['device_type'] : 'unknown',
                'browser_family' => isset($properties['browser_family']) ? $properties['browser_family'] : 'unknown',
                'browser_version' => isset($properties['browser_version']) ? $properties['browser_version'] : 'unknown',
                'device_family' => isset($properties['device_family']) ? $properties['device_family'] : 'unknown',
                'device_model' => isset($properties['device_model']) ? $properties['device_model'] : 'unknown',
            ];
        });

        $deviceIcon = [
            'Linux' => 'bxl-linux',
            'Windows' => 'bxl-windows',
            'Mac' => 'bxl-apple',
            'Android' => 'bxl-android',
            'iPhone' => 'bxl-apple',
            'iPad' => 'bxl-apple',
            'unknown' => 'bx-windows',
        ];

        return compact('user', 'recentLogins', 'deviceIcon');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request): bool
    {
        $user = auth('web')->user();

        if (
            isset($request['current_password']) &&
            !password_verify($request['current_password'], $user->password)
        ) {
            return false;
        }

        $user->update([
            'password' => $request['password'] ?? $user->password,
        ]);

        return true;
    }
}
