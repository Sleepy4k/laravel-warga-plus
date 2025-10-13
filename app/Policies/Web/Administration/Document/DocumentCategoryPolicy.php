<?php

namespace App\Policies\Web\Administration\Document;

use App\Models\DocumentCategory;
use App\Models\User;

class DocumentCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('document.category.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DocumentCategory $documentCategory): bool
    {
        return $user->can('document.category.show');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('document.category.store');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DocumentCategory $documentCategory): bool
    {
        return $user->can('document.category.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DocumentCategory $documentCategory): bool
    {
        return $user->can('document.category.delete');
    }
}
