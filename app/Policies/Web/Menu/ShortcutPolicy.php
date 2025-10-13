<?php

namespace App\Policies\Web\Menu;

use App\Models\Shortcut;
use App\Models\User;

class ShortcutPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('menu.shortcut.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Shortcut $shortcut): bool
    {
        return $user->can('menu.shortcut.show');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('menu.shortcut.store');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Shortcut $shortcut): bool
    {
        return $user->can('menu.shortcut.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Shortcut $shortcut): bool
    {
        return $user->can('menu.shortcut.delete');
    }
}
