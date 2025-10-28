<?php

namespace App\Services\Install;

use App\Contracts\Models\RoleInterface;
use App\Contracts\Models\UserAgreementInterface;
use App\Contracts\Models\UserInterface;
use App\Contracts\Models\UserPersonalDataInterface;
use App\Enums\Gender;
use App\Foundations\Service;
use Illuminate\Support\Facades\Artisan;

class UserService extends Service
{
    /**
     * Create a new service instance.
     *
     * @param UserInterface $userInterface
     */
    public function __construct(
        private UserInterface $userInterface,
        private RoleInterface $roleInterface,
        private UserPersonalDataInterface $userPersonalDataInterface,
        private UserAgreementInterface $userAgreementInterface
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
     * @return void
     */
    public function store(array $request): void
    {
        try {
            // Check total roles
            $roles = $this->roleInterface->count();

            // If there are no roles, run the seeder
            if ($roles === 0) {
                // Run the seeder for permission and role
                Artisan::call('db:seed', ['--class' => 'PermissionSeeder']);
                Artisan::call('db:seed', ['--class' => 'RoleSeeder']);
            }

            // Super admin role name
            $superAdminRole = config('rbac.role.highest');
            if (!$superAdminRole) throw new \Exception('Super admin role name is not set in the config file.');

            // Check if role super admin exists
            $role = $this->roleInterface->findByCustomId(['name' => $superAdminRole], ['name']);

            // If role super admin does not exist, create it
            if (!$role) $role = $this->roleInterface->create(['name' => $superAdminRole, 'guard_name' => 'web']);

            // Add role to payload
            $request['role'] = $role->name;

            // Create the user
            $request['verified_at'] = now();
            $user = $this->userInterface->create($request);

            // Create user personal data
            $this->userPersonalDataInterface->create([
                'id' => \Illuminate\Support\Str::uuid(),
                'user_id' => $user->id,
                'first_name' => 'Admin',
                'last_name' => 'Warga Plus',
                'gender' => Gender::Male->value,
                'birth_date' => now()->subYears(20)->toDateString(),
                'job' => 'Administrator',
                'address' => 'Jl. Hipmi No. 1',
            ]);

            // Create user agreement
            $this->userAgreementInterface->create([
                'id' => \Illuminate\Support\Str::uuid(),
                'user_id' => $user->id,
                'agreement' => true,
                'privacy_policy' => true,
                'newsletter' => false,
            ]);
        } catch (\Exception $e) {
            // Check if the user already created
            $user = $this->userInterface->findByCustomId(['phone' => $request['phone']], ['id']);

            // If the user already created, delete the user
            if ($user) $this->userInterface->deleteById($user->id);

            throw new \Exception('Could not create the user: '.$e->getMessage());
        }
    }
}
