<?php

namespace App\Policies\Web\Administration\Reference;

use App\Models\LetterStatus;
use App\Models\User;

class StatusPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('letter_references.status.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LetterStatus $letterStatus): bool
    {
        return $user->can('letter_references.status.show');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('letter_references.status.store');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LetterStatus $letterStatus): bool
    {
        return $user->can('letter_references.status.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LetterStatus $letterStatus): bool
    {
        return $user->can('letter_references.status.delete');
    }
}
