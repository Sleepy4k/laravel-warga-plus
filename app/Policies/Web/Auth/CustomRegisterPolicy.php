<?php

namespace App\Policies\Web\Auth;

use App\Models\User;

class CustomRegisterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        try {
            $payload = request()->query('payload');
            if (!$payload) return false;

            $data = decrypt($payload);
            if (!$data) return false;

            $validData = $user->id . '|' . $user->email;

            return !$user->personal()->exists() && $data === $validData;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user): bool
    {
        return !$user->personal()->exists();
    }
}
