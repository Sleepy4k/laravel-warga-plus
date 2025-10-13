<?php

namespace App\Policies\Web\Administration\Reference;

use App\Models\LetterClassification;
use App\Models\User;

class ClassificationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('letter_references.classification.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LetterClassification $letterClassification): bool
    {
        return $user->can('letter_references.classification.show');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('letter_references.classification.store');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LetterClassification $letterClassification): bool
    {
        return $user->can('letter_references.classification.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LetterClassification $letterClassification): bool
    {
        return $user->can('letter_references.classification.delete');
    }
}
