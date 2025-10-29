<?php

namespace App\Http\Controllers\Web\Dashboard\User;

use App\DataTables\User\ReportDataTable;
use App\DataTables\User\UserDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\User\StoreRequest;
use App\Http\Requests\Web\Dashboard\User\UpdateRequest;
use App\Models\User;
use App\Services\Web\Dashboard\User\ListService;
use App\Traits\Authorizable;

class ListController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ListService $service,
        private $policy = User::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
            'show' => 'view',
            'update' => 'update',
            'destroy' => 'delete',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.user.list', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create user. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getUserStats(), 'User successfully created.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ReportDataTable $dataTable, User $user)
    {
        return $dataTable->render('pages.dashboard.user.detail', $this->service->show($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user)
    {
        if (!$this->service->update($request->validated(), $user)) {
            return $this->sendResponse(null, 'Failed to update user. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getUserStats(), 'User successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (!$this->service->destroy($user)) {
            return $this->sendResponse(null, 'Failed to delete user. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getUserStats(), 'User successfully deleted.', 200);
    }
}
