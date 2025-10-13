<?php

namespace App\Http\Controllers\Web\Dashboard\Activity;

use App\DataTables\Activity\ActivityCategoryDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Activity\StoreCategoryRequest;
use App\Http\Requests\Web\Dashboard\Activity\UpdateCategoryRequest;
use App\Models\ActivityCategory;
use App\Services\Web\Dashboard\Activity\ActivityCategoryService;
use App\Traits\Authorizable;

class ActivityCategoryController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ActivityCategoryService $service,
        private $policy = ActivityCategory::class,
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
    public function index(ActivityCategoryDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.activity.category.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create activity category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Activity category successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, ActivityCategory $activityCategory)
    {
        if (!$this->service->update($request->validated(), $activityCategory)) {
            return $this->sendResponse(null, 'Failed to update activity category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Activity category successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityCategory $activityCategory)
    {
        if (!$this->service->destroy($activityCategory)) {
            return $this->sendResponse(null, 'Failed to delete activity category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Activity category successfully deleted.', 200);
    }
}
