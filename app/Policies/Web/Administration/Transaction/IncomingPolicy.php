<?php

namespace App\Policies\Web\Administration\Transaction;

use App\Models\User;

class IncomingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('letter_transaction.incoming.index');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return $user->can('letter_transaction.incoming.store');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->can('letter_transaction.incoming.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->can('letter_transaction.incoming.delete');
    }
}
