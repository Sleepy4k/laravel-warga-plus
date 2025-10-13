<?php

namespace App\Http\Controllers\Web\Dashboard\Administration\Document;

use App\DataTables\Administration\Document\DocumentCategoryDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Administration\Document\Category\StoreRequest;
use App\Http\Requests\Web\Dashboard\Administration\Document\Category\UpdateRequest;
use App\Models\DocumentCategory;
use App\Services\Web\Dashboard\Administration\Document\DocumentCategoryService;
use App\Traits\Authorizable;

class DocumentCategoryController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private DocumentCategoryService $service,
        private $policy = DocumentCategory::class,
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
    public function index(DocumentCategoryDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.administration.document.category.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create document category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Document category successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, DocumentCategory $category)
    {
        if (!$this->service->update($request->validated(), $category)) {
            return $this->sendResponse(null, 'Failed to update document category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Document category successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentCategory $category)
    {
        if (!$this->service->destroy($category)) {
            return $this->sendResponse(null, 'Failed to delete document category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Document category successfully deleted.', 200);
    }
}
