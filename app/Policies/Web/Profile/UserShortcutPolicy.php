<?php

namespace App\Policies\Web\Profile;

use App\Models\Shortcut;
use App\Models\User;

class UserShortcutPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Shortcut $shortcut): bool
    {
        if (empty($shortcut->permissions)) {
            return true;
        }

        return $user->canAny($shortcut->permissions);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Shortcut $shortcut): bool
    {
        if (empty($shortcut->permissions)) {
            return true;
        }

        return $user->canAny($shortcut->permissions);
    }
}
