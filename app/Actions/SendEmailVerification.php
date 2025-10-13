<?php

namespace App\Actions;

use App\Models\User;
use App\Notifications\EmailVerification;
use Illuminate\Support\Facades\URL;

class SendEmailVerification
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
    public function execute(User $user): bool
    {
        if (!$user || is_null($user)) {
            return false;
        }

        try {
            $url = $this->generateActionUrl($user);
            $fullname = $user->personal->full_name;

            $user->notify(new EmailVerification($fullname, $url));

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Generate a signed URL for email verification.
     *
     * @param User $user
     * @return string
     */
    private function generateActionUrl(User $user): string
    {
        $hash = sha1($user->getEmailForVerification());
        $expiration = now()->addMinutes((int) config('auth.verification.expire', 60));

        return URL::temporarySignedRoute('verification.verify', $expiration, [
            'id' => $user->getKey(),
            'hash' => $hash,
        ]);
    }
}
