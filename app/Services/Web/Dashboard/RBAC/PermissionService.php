<?php

namespace App\Services\Web\Dashboard\RBAC;

use App\Foundations\Service;
use App\Models\Permission;
use App\Traits\GuardNameData;

class PermissionService extends Service
{
    use GuardNameData;

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $guardList = $this->getGuardNameList();
        $defaultGuard = $this->getDefaultGuardName();

        return compact('guardList', 'defaultGuard');
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
        $permission = Permission::create([
            'name' => $request['name'],
            'guard_name' => $request['guard_name'],
        ]);

        return $permission ? true : false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $request
     * @param int $id
     *
     * @return bool
     */
    public function update(array $request, Permission $permission): bool
    {
        try {
            $permission->update([
                'name' => $request['name'],
                'guard_name' => $request['guard_name'],
            ]);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroy(Permission $permission): bool
    {
        try {
            if ($permission->roles()->count() > 0) {
                $permission->roles()->each(function ($role) {
                    $role->revokePermissionTo($role->permissions);
                });
            }

            $permission->delete();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
