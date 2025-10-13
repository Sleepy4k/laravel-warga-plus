<?php

namespace App\Policies\Web\Administration\Transaction;

use App\Models\Letter;
use App\Models\LetterDisposition;
use App\Models\User;

class DispositionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('letter_transaction.disposition.index');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('letter_transaction.disposition.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Letter $letter, LetterDisposition $letterDisposition): bool
    {
        if ($letter->id !== $letterDisposition->letter_id) {
            return false;
        }

        return $user->can('letter_transaction.disposition.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Letter $letter, LetterDisposition $letterDisposition): bool
    {
        if ($letter->id !== $letterDisposition->letter_id) {
            return false;
        }

        return $user->can('letter_transaction.disposition.delete');
    }
}
