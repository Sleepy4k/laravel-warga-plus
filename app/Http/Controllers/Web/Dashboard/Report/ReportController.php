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
        return $dataTable->render('pages.dashboard.report.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
