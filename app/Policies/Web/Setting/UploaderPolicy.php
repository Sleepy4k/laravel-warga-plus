<?php

namespace App\Policies\Web\Setting;

use App\Models\User;

class UploaderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('setting.uploader.index');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('setting.uploader.store');
    }
}
