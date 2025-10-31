<?php

namespace App\Policies\Web\Report;

use App\Models\ReportCategory;
use App\Models\User;

class ReportCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('report.category.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ReportCategory $reportCategory): bool
    {
        return $user->can('report.category.show');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('report.category.store');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReportCategory $reportCategory): bool
    {
        return $user->can('report.category.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReportCategory $reportCategory): bool
    {
        return $user->can('report.category.delete');
    }
}
