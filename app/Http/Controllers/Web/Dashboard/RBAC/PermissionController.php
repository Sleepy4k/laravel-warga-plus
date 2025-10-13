<?php

namespace App\Http\Controllers\Web\Dashboard\RBAC;

use App\DataTables\RBAC\PermissionDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\RBAC\StorePermissionRequest;
use App\Http\Requests\Web\Dashboard\RBAC\UpdatePermissionRequest;
use App\Models\Permission;
use App\Services\Web\Dashboard\RBAC\PermissionService;
use App\Traits\Authorizable;

class PermissionController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private PermissionService $service,
        private $policy = Permission::class,
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
    public function index(PermissionDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.rbac.permission.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create permission. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Permission successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        if (!$this->service->update($request->validated(), $permission)) {
            return $this->sendResponse(null, 'Failed to update permission. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Permission successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        if (!$this->service->destroy($permission)) {
            return $this->sendResponse(null, 'Failed to delete permission. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Permission successfully deleted.', 200);
    }
}
