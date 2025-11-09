<?php

namespace App\Policies\Web\Information;

use App\Models\InformationCategory;
use App\Models\User;

class InformationCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('information.category.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InformationCategory $informationCategory): bool
    {
        return $user->can('information.category.show');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('information.category.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InformationCategory $informationCategory): bool
    {
        return $user->can('information.category.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InformationCategory $informationCategory): bool
    {
        return $user->can('information.category.delete');
    }
}
