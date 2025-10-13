<?php

namespace App\Services\Web\Dashboard\Profile;

use App\Contracts\Models\ArticleInterface;
use App\Foundations\Service;
use App\Models\User;

class SettingService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private ArticleInterface $articleInterface,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $user = auth('web')->user()->load('personal:user_id,first_name,last_name,whatsapp_number,address,avatar');

        return [
            'user' => $user,
            'personal' => $user->personal,
        ];
    }
    /**
     * Update the specified resource in storage.
     *
     * @param array $request
     *
     * @return bool
     */
    public function update(array $request): bool
    {
        $user = auth('web')->user();

        try {
            $userData = array_filter([
                'password' => $request['password'] ?? null,
                'email' => $request['email'] ?? null,
            ], fn($value) => !empty($value));

            if (!empty($userData)) {
                $user->update($userData);
                $user->refresh();
            }

            $personalData = [
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'whatsapp_number' => $request['whatsapp_number'],
                'address' => $request['address'],
            ];

            if (isset($request['avatar']) && !empty($request['avatar'])) {
                $personalData['avatar'] = $request['avatar'];
            }

            $user->personal->update($personalData);
            $user->personal->refresh();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Delete the specified resource from storage.
     *
     * @return bool
     */
    public function delete(): bool
    {
        $user = auth('web')->user();

        try {
            if (!auth('web')->check() || !auth('web')->user()->is($user)) {
                return false;
            }

            auth('web')->logout();
            $user->delete();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
