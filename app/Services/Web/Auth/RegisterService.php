<?php

namespace App\Services\Web\Auth;

use App\Contracts\Models\UserAgreementInterface;
use App\Contracts\Models\UserInterface;
use App\Contracts\Models\UserPersonalDataInterface;
use App\Facades\Toast;
use App\Foundations\Service;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private UserInterface $userInterface,
        private UserAgreementInterface $userAgreementInterface,
        private UserPersonalDataInterface $userPersonalDataInterface,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        return [];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     *
     * @return bool
     */
    public function store(array $request): bool
    {
        $user = $this->userInterface->create([
            'email' => $request['email'],
            'username' => $request['username'],
            'password' => $request['password'],
        ]);

        if (!$user) {
            Toast::danger('Error', 'The provided email or username is already registered.');
            return false;
        }

        $personalData = $this->userPersonalDataInterface->create([
            'user_id' => $user->id,
            'first_name' => $request['first_name'] ?? null,
            'last_name' => $request['last_name'] ?? null,
            'whatsapp_number' => $request['whatsapp_number'] ?? null,
            'telkom_batch' => $request['telkom_batch'] ?? null,
            'address' => $request['address'] ?? null,
        ]);

        if (!$personalData) {
            $user->delete();
            Toast::danger('Error', 'An error occurred while saving personal data.');
            return false;
        }

        $trueValues = ['on', true, '1', 1];
        $agreement = $this->userAgreementInterface->create([
            'user_id' => $user->id,
            'agreement' => !empty($request['agreement']) && in_array($request['agreement'], $trueValues, true),
            'privacy_policy' => !empty($request['privacy_policy']) && in_array($request['privacy_policy'], $trueValues, true),
            'newsletter' => !empty($request['newsletter']) && in_array($request['newsletter'], $trueValues, true),
        ]);

        if (!$agreement) {
            $user->delete();
            $personalData->delete();
            Toast::danger('Error', 'An error occurred while saving user agreement.');
            return false;
        }

        Toast::primary('Success', 'Registration successful.');

        Auth::login($user);
        session()->regenerate();

        event(new Registered($user));

        return true;
    }
}
