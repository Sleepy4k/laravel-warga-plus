<?php

namespace App\Actions;

use App\Models\User;
use App\Notifications\RequestResetPassword;

class SendRequestResetPassword
{
    /**
     * Action contract constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the action.
     *
     * @param User $user
     * @return bool
     */
    public function execute(User $user, string $token): bool
    {
        if (!$user || is_null($user)) {
            return false;
        }

        try {
            $url = $this->generateActionUrl($user, $token);
            $fullname = $user->personal->full_name;

            $user->notify(new RequestResetPassword($fullname, $url));

            return true;
        } catch (\Throwable $th) {
            dd($th);
            return false;
        }
    }

    /**
     * Generate a signed URL for email verification.
     *
     * @param User $user
     * @return string
     */
    private function generateActionUrl(User $user, string $token): string
    {
        return url(route('password.reset', ['token' => $token, 'phone' => $user->phone], false));
    }
}
