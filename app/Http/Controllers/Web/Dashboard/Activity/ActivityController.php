<?php

namespace App\Http\Controllers\Web\Dashboard\Activity;

use App\DataTables\Activity\ActivityDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Activity\StoreRequest;
use App\Http\Requests\Web\Dashboard\Activity\UpdateRequest;
use App\Models\Activity;
use App\Services\Web\Dashboard\Activity\ActivityService;
use App\Traits\Authorizable;

class ActivityController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ActivityService $service,
        private $policy = Activity::class,
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
    public function index(ActivityDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.activity.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create activity. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getActivityStats(), 'Activity successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Activity $activity)
    {
        if (!$this->service->update($request->validated(), $activity)) {
            return $this->sendResponse(null, 'Failed to update activity. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getActivityStats(), 'Activity successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        if (!$this->service->destroy($activity)) {
            return $this->sendResponse(null, 'Failed to delete activity. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getActivityStats(), 'Activity successfully deleted.', 200);
    }
}
