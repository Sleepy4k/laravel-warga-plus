<?php

namespace App\Http\Controllers\Web\Dashboard\RBAC;

use App\DataTables\RBAC\RoleDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\RBAC\StoreRoleRequest;
use App\Http\Requests\Web\Dashboard\RBAC\UpdateRoleRequest;
use App\Models\Role;
use App\Services\Web\Dashboard\RBAC\RoleService;
use App\Traits\Authorizable;

class RoleController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private RoleService $service,
        private $policy = Role::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
            'update' => 'update',
            'destroy' => 'delete',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.rbac.role.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create role. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Role successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $role)
    {
        if (!$this->service->update($request->validated(), $role)) {
            return $this->sendResponse(null, 'Failed to update role. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Role successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $role)
    {
        if (!$this->service->destroy($role)) {
            return $this->sendResponse(null, 'Failed to delete role. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Role successfully deleted.', 200);
    }
}
