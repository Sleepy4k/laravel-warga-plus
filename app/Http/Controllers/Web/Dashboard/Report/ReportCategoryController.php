<?php

namespace App\Http\Controllers\Web\Dashboard\Report;

use App\DataTables\Report\ReportCategoryDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Report\Category\StoreRequest;
use App\Http\Requests\Web\Dashboard\Report\Category\UpdateRequest;
use App\Models\ReportCategory;
use App\Services\Web\Dashboard\Report\ReportCategoryService;
use App\Traits\Authorizable;

class ReportCategoryController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ReportCategoryService $service,
        private $policy = ReportCategory::class,
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
    public function index(ReportCategoryDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.report.category.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create report category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Report category successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, ReportCategory $category)
    {
        if (!$this->service->update($request->validated(), $category)) {
            return $this->sendResponse(null, 'Failed to update report category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Report category successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportCategory $category)
    {
        if (!$this->service->destroy($category)) {
            return $this->sendResponse(null, 'Failed to delete report category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Report category successfully deleted.', 200);
    }
}
