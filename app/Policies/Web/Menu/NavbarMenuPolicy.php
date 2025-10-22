<?php

namespace App\Policies\Web\Menu;

use App\Models\NavbarMenu;
use App\Models\User;

class NavbarMenuPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('menu.navbar.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, NavbarMenu $navbarMenu): bool
    {
        return $user->can('menu.navbar.show');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('menu.navbar.store');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, NavbarMenu $navbarMenu): bool
    {
        return $user->can('menu.navbar.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, NavbarMenu $navbarMenu): bool
    {
        return $user->can('menu.navbar.delete');
    }

    /**
     * Determine whether the user can save order of the model.
     */
    public function saveOrder(User $user): bool
    {
        return $user->can('menu.navbar.update');
    }
}
