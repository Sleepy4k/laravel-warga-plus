<?php

namespace App\Policies\Install;

use App\Models\User;

class SetupPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user, bool $isPreviousStepCompleted): bool
    {
        $isAlreadyInstalled = file_exists(storage_path('.installed'));

        return !$isAlreadyInstalled && $isPreviousStepCompleted;
    }
}
