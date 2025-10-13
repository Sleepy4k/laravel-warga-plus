<?php

namespace App\Http\Controllers\Web\Dashboard\Administration\Reference;

use App\DataTables\Administration\Reference\ClassificationDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Administration\Reference\Classification\StoreRequest;
use App\Http\Requests\Web\Dashboard\Administration\Reference\Classification\UpdateRequest;
use App\Models\LetterClassification;
use App\Policies\Web\Administration\Reference\ClassificationPolicy;
use App\Services\Web\Dashboard\Administration\Reference\ClassificationService;
use App\Traits\Authorizable;

class ClassificationController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ClassificationService $service,
        private $policy = ClassificationPolicy::class,
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
    public function index(ClassificationDataTable $datatable)
    {
        return $datatable->render('pages.dashboard.administration.reference.classification.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create classification. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Classification successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, LetterClassification $classification)
    {
        if (!$this->service->update($request->validated(), $classification)) {
            return $this->sendResponse(null, 'Failed to update classification. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Classification successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LetterClassification $classification)
    {
        if (!$this->service->destroy($classification)) {
            return $this->sendResponse(null, 'Failed to delete classification. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Classification successfully deleted.', 200);
    }
}
