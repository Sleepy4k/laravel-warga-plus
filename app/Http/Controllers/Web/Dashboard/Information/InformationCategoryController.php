<?php

namespace App\Http\Controllers\Web\Dashboard\Information;

use App\DataTables\Information\InformationCategoryDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Information\Category\StoreRequest;
use App\Http\Requests\Web\Dashboard\Information\Category\UpdateRequest;
use App\Models\InformationCategory;
use App\Services\Web\Dashboard\Information\InformationCategoryService;
use App\Traits\Authorizable;

class InformationCategoryController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private InformationCategoryService $service,
        private $policy = InformationCategory::class,
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
    public function index(InformationCategoryDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.information.category.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create information category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Information category successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, InformationCategory $category)
    {
        if (!$this->service->update($request->validated(), $category)) {
            return $this->sendResponse(null, 'Failed to update information category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Information category successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InformationCategory $category)
    {
        if (!$this->service->destroy($category)) {
            return $this->sendResponse(null, 'Failed to delete information category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Information category successfully deleted.', 200);
    }
}
