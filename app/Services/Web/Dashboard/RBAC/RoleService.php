<?php

namespace App\Services\Web\Dashboard\RBAC;

use App\Contracts\Models;
use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\Permission;
use App\Traits\GuardNameData;

class RoleService extends Service
{
    use GuardNameData;

    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\RoleInterface $roleInterface,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $guardList = $this->getGuardNameList();
        $defaultGuard = $this->getDefaultGuardName();

        $cardRoles = $this->roleInterface->all(['id', 'name'], ['users:id'])
            ->filter(function ($role) {
                return $role->users->isNotEmpty();
            })
            ->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'total_users' => $role->users->count(),
                ];
            });

        $permissions = Permission::query()
            ->select(['id', 'name', 'guard_name'])
            ->get()
            ->groupBy(function ($permission) {
                $parts = explode('.', $permission->name);
                array_pop($parts);
                return ucfirst(implode('.', $parts));
            });

        return compact('cardRoles', 'permissions', 'guardList', 'defaultGuard');
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
        try {
            $request['name'] = strtolower($request['name']);
            $role = $this->roleInterface->create($request);

            if (!$role) {
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create role', [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $request
     * @param string $id
     *
     * @return bool
     */
    public function update(array $request, string $id): bool
    {
        try {
            $request['name'] = strtolower($request['name']);
            $isSuccess = $this->roleInterface->update($id, $request);

            if (!$isSuccess) {
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update role', [
                'request' => $request,
                'role_id' => $id,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     *
     * @return bool
     */
    public function destroy(string $id): bool
    {
        try {
            $role = $this->roleInterface->findById($id, ['*'], ['permissions', 'users']);

            if ($role->permissions->isNotEmpty()) {
                $role->permissions()->detach();
            }

            if ($role->users->isNotEmpty()) {
                foreach ($role->users as $user) {
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
                }
            }

            $isSuccess = $this->roleInterface->deleteById($id);

            if (!$isSuccess) {
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete role', [
                'role_id' => $id,
            ]);
            return false;
        }
    }
}
