<?php

namespace App\Policies\Web\Activity;

use App\Models\ActivityCategory;
use App\Models\User;

class ActivityCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('activity.category.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ActivityCategory $activityCategory): bool
    {
        return $user->can('activity.category.show');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('activity.category.store');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ActivityCategory $activityCategory): bool
    {
        return $user->can('activity.category.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ActivityCategory $activityCategory): bool
    {
        return $user->can('activity.category.delete');
    }
}
