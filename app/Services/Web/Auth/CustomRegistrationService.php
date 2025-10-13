<?php

namespace App\Services\Web\Auth;

use App\Contracts\Models\UserAgreementInterface;
use App\Contracts\Models\UserPersonalDataInterface;
use App\Facades\Toast;
use App\Foundations\Service;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class CustomRegistrationService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private UserAgreementInterface $userAgreementInterface,
        private UserPersonalDataInterface $userPersonalDataInterface,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        return [];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        $user = auth('web')->user();

        if (!$user) {
            Toast::danger('Error', 'User not found.');
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
