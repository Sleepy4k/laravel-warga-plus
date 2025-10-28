<?php

namespace App\Services\Web\Dashboard\Profile;

use App\Enums\Gender;
use App\Foundations\Service;
use Illuminate\Support\Facades\Auth;

class SettingService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct() {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $genders = Gender::cases();
        $user = auth('web')->user()->load('personal:user_id,first_name,last_name,job,address,gender,birth_date,avatar');

        return [
            'user' => $user,
            'genders' => $genders,
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
                'phone' => $request['phone'] ?? null,
            ], fn($value) => !empty($value));

            if (!empty($userData)) {
                $user->update($userData);
                $user->refresh();
            }

            $personalData = [
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'birth_date' => $request['birth_date'],
                'gender' => $request['gender'],
                'job' => $request['job'],
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

            Auth::logout();
            $user->delete();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
