<?php

namespace App\Http\Controllers\Web\Dashboard\Report;

use App\DataTables\Report\ReportDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Report\StoreRequest;
use App\Http\Requests\Web\Dashboard\Report\UpdateRequest;
use App\Models\Report;
use App\Services\Web\Dashboard\Report\ReportService;
use App\Traits\Authorizable;

class ReportController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ReportService $service,
        private $policy = Report::class,
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
    public function index(ReportDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.report.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create report. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getReportStats(), 'Report successfully created.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Report $report)
    {
        if (!$this->service->update($request->validated(), $report)) {
            return $this->sendResponse(null, 'Failed to update report. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getReportStats(), 'Report successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        if (!$this->service->destroy($report)) {
            return $this->sendResponse(null, 'Failed to delete report. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getReportStats(), 'Report successfully deleted.', 200);
    }
}
