<?php

namespace App\Http\Controllers\Web\Dashboard\Administration\Reference;

use App\DataTables\Administration\Reference\StatusDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Administration\Reference\Status\StoreRequest;
use App\Http\Requests\Web\Dashboard\Administration\Reference\Status\UpdateRequest;
use App\Models\LetterStatus;
use App\Policies\Web\Administration\Reference\StatusPolicy;
use App\Services\Web\Dashboard\Administration\Reference\StatusService;
use App\Traits\Authorizable;

class StatusController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private StatusService $service,
        private $policy = StatusPolicy::class,
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
    public function index(StatusDataTable $datatable)
    {
        return $datatable->render('pages.dashboard.administration.reference.status.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create status. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Status successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, LetterStatus $status)
    {
        if (!$this->service->update($request->validated(), $status)) {
            return $this->sendResponse(null, 'Failed to update status. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Status successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LetterStatus $status)
    {
        if (!$this->service->destroy($status)) {
            return $this->sendResponse(null, 'Failed to delete status. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Status successfully deleted.', 200);
    }
}
