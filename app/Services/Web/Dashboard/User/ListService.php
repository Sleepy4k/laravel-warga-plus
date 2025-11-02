<?php

namespace App\Services\Web\Dashboard\User;

use App\Contracts\Models;
use App\Enums\Gender;
use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\Role;
use App\Models\User;
use App\Notifications\RegisteredByAdmin;
use Spatie\Activitylog\Models\Activity;

class ListService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\UserInterface $userInterface,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $genders = Gender::cases();
        $roles = Role::select('name')->get();
        $users = $this->userInterface->all(['id', 'is_active']);
        $totalUsers = $users->count();
        $totalManagers = $users->filter(fn($user) => !$user->hasRole(config('rbac.role.default')) && !$user->hasRole(config('rbac.role.highest')) && $user->is_active)->count();
        $totalActiveUsers = $users->filter(fn($user) => $user->is_active)->count();
        $totalInactiveUsers = $users->filter(fn($user) => !$user->is_active)->count();

        // set roles data for security, so admin can assign role but not above admin role
        $userRole = getUserRole();
        $isRoleHighest = $userRole == config('rbac.role.highest');
        if ($userRole == config('rbac.role.default')) {
            $assignableRoles = [config('rbac.role.default')];
        } else if ($isRoleHighest) {
            $assignableRoles = config('rbac.list.roles');
        } else {
            $assignableRoles = array_filter(config('rbac.assign'), fn($roles) => in_array($userRole, $roles));
            $assignableRoles = array_keys($assignableRoles);
            $assignableRoles = array_intersect($assignableRoles, $roles->pluck('name')->toArray());
        }

        return compact(
            'genders',
            'assignableRoles',
            'totalUsers',
            'totalManagers',
            'totalActiveUsers',
            'totalInactiveUsers'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        try {
            $password = str()->random(16);

            $user = User::create([
                'phone' => $request['phone'],
                'identity_number' => $request['identity_number'],
                'password' => $password,
                'is_active' => true,
            ]);

            $user->assignRole($request['role'] ?? config('rbac.role.default'));

            $otherPhone = $request['other-phone'] ?? null;

            $user->notify(new RegisteredByAdmin($request['phone'], $request['identity_number'], $password, $otherPhone));

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create user: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): array
    {
        $genders = Gender::cases();
        $roles = Role::select('name')->get();
        $currentUser = $user->loadMissing('personal:user_id,first_name,last_name,birth_date,job,gender,address,avatar');
        $personal = $currentUser->personal;

        $recentLogins = Activity::where('causer_id', $user->id)
            ->where('log_name', 'auth')
            ->where('event', 'login')
            ->orderBy('created_at', 'desc')
            ->limit(11)
            ->get(['id', 'properties', 'created_at']);

        $recentLogins = $recentLogins->map(function ($login) {
            $properties = $login->properties;
            return [
                'id' => $login->id,
                'login_at' => $login->created_at->format('d, F Y H:i'),
                'plain_login_at' => $login->created_at,
                'ip_address' => $properties['ip_address'] ?? 'N/A',
                'device_type' => isset($properties['device_type']) ? $properties['device_type'] : 'unknown',
                'browser_family' => isset($properties['browser_family']) ? $properties['browser_family'] : 'unknown',
                'browser_version' => isset($properties['browser_version']) ? $properties['browser_version'] : 'unknown',
                'device_family' => isset($properties['device_family']) ? $properties['device_family'] : 'unknown',
                'device_model' => isset($properties['device_model']) ? $properties['device_model'] : 'unknown',
            ];
        });

        $deviceIcon = [
            'Linux' => 'bxl-linux',
            'Windows' => 'bxl-windows',
            'Mac' => 'bxl-apple',
            'Android' => 'bxl-android',
            'iPhone' => 'bxl-apple',
            'iPad' => 'bxl-apple',
            'unknown' => 'bx-windows',
        ];

        // set roles data for security, so admin can assign role but not above admin role
        $userRole = getUserRole();
        if ($userRole == config('rbac.role.default')) {
            $assignableRoles = [config('rbac.role.default')];
        } else if ($userRole == config('rbac.role.highest')) {
            $assignableRoles = config('rbac.list.roles');
        } else {
            $assignableRoles = array_filter(config('rbac.assign'), fn($roles) => in_array($userRole, $roles));
            $assignableRoles = array_keys($assignableRoles);
            $assignableRoles = array_intersect($assignableRoles, $roles->pluck('name')->toArray());
        }

        return [
            'genders' => $genders,
            'uid' => $currentUser->id,
            'user' => $currentUser,
            'role' => $currentUser->getRoleNames()->first() ?? 'N/A',
            'personal' => $personal,
            'recentLogins' => $recentLogins,
            'deviceIcon' => $deviceIcon,
            'assignableRoles' => $assignableRoles,
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, User $user): bool
    {
        try {
            $isActive = $request['is_active'] ?? false;
            unset($request['is_active']);

            if ($isActive && !$user->is_active) {
                $user->activate();
            } else if (!$isActive && $user->is_active) {
                $user->deactivate();
            }

            $user->update([
                'is_active' => $isActive,
            ]);

            $user->personal()->updateOrCreate(
                ['user_id' => $user->id],
                $request
            );

            if ($request['role'] !== getUserRole()) {
                $user->syncRoles($request['role']);
            }

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update user: ' . $th->getMessage(), [
                'user_id' => $user->id,
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): bool
    {
        try {
            $agreements = $user->agreement();
            if ($agreements->count() > 0) {
                $agreements->delete();
            }

            $personals = $user->personal();
            if ($personals->count() > 0) {
                $personals->delete();
            }

            if (!$user->is_active) {
                $user->temporaryRole()->delete();
            }

            $user->delete();
            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete user: ' . $th->getMessage(), [
                'user_id' => $user->id,
            ]);
            return false;
        }
    }

    /**
     * Get user statistics.
     *
     * @return array
     */
    public function getUserStats(): array
    {
        $users = $this->userInterface->all(['id', 'is_active']);
        $totalUsers = $users->count();
        $totalManagers = $users->filter(fn($user) => !$user->hasRole(config('rbac.role.default')) && !$user->hasRole(config('rbac.role.highest')) && $user->is_active)->count();
        $totalActiveUsers = $users->filter(fn($user) => $user->is_active)->count();
        $totalInactiveUsers = $users->filter(fn($user) => !$user->is_active)->count();

        return compact(
            'totalUsers',
            'totalManagers',
            'totalActiveUsers',
            'totalInactiveUsers'
        );
    }
}
