<?php

namespace App\Services\Web\Dashboard\Profile;

use App\Foundations\Service;
use Illuminate\Http\Request;

class HeartbeatService extends Service
{
    /**
     * Handle the incoming request.
     */
    public function invoke(Request $request): void
    {
        $data = $request->validate([
            'isFocused' => ['nullable', 'boolean'],
        ]);

        $user = auth('web')->user();

        if ($user) {
            if (($data['isFocused'] ?? false) && (!$user->last_seen || $user->last_seen->diffInMinutes(now()) > 2)) {
                $user->forceFill(['last_seen' => now()])->saveQuietly();
            }
        }
    }
}
