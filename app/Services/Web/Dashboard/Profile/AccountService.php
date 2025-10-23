<?php

namespace App\Services\Web\Dashboard\Profile;

use App\Contracts\Models;
use App\Foundations\Service;

class AccountService extends Service
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $user = auth('web')->user()->load('personal:user_id,first_name,last_name,job,address,avatar');
        $personal = $user->personal;

        return [
            'user' => $user,
            'role' => $user->getRoleNames()->first() ?? "Guest",
            'personal' => $personal,
        ];
    }
}
