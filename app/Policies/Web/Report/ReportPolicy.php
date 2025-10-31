<?php

namespace App\Policies\Web\Report;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('report.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Report $report): bool
    {
        $isAllowed = $user->can('report.show');

        if (!isUserHasRole(config('rbac.role.default'))) {
            return $isAllowed;
        }

        return $report->user_id === $user->id && $isAllowed;
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('report.store');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Report $report): bool
    {
        $isAllowed = $user->can('report.update');

        if (!isUserHasRole(config('rbac.role.default'))) {
            return $isAllowed;
        }

        return $report->user_id === $user->id && $isAllowed;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        $isAllowed = $user->can('report.delete');

        if (!isUserHasRole(config('rbac.role.default'))) {
            return $isAllowed;
        }

        return $report->user_id === $user->id && $isAllowed;
    }
}
