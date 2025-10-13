<?php

namespace App\Policies\Web\Log;

use App\Models\User;

class ModelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('log.model.index');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('log.model.store');
    }
}
