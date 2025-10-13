<?php

namespace App\Policies\Web\Log;

use App\Models\User;

class AuthPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('log.auth.index');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('log.auth.store');
    }
}
