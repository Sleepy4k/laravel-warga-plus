<?php

namespace App\Http\Controllers\Web\Dashboard\Information;

use App\DataTables\Information\InformationDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Information\StoreRequest;
use App\Http\Requests\Web\Dashboard\Information\UpdateRequest;
use App\Models\Information;
use App\Services\Web\Dashboard\Information\InformationService;
use App\Traits\Authorizable;

class InformationController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private InformationService $service,
        private $policy = Information::class,
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
    public function index(InformationDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.information.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create information. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getInformationStats(), 'Information successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Information $information)
    {
        if (!$this->service->update($request->validated(), $information)) {
            return $this->sendResponse(null, 'Failed to update information. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getInformationStats(), 'Information successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Information $information)
    {
        if (!$this->service->destroy($information)) {
            return $this->sendResponse(null, 'Failed to delete information. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getInformationStats(), 'Information successfully deleted.', 200);
    }
}
