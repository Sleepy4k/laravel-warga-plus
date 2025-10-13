<?php

namespace App\Http\Controllers\Web\Dashboard\Log;

use App\DataTables\Log\QueryDataTable;
use App\DataTables\Log\QueryDetailDataTable;
use App\Foundations\Controller;
use App\Policies\Web\Log\QueryPolicy;
use App\Services\Web\Dashboard\Log\QueryService;
use App\Traits\Authorizable;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private QueryService $service,
        private $policy = QueryPolicy::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
            'show' => 'view',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(QueryDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.log.query.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->service->store($request->get('log_name'))) {
            return $this->sendResponse(null, 'Failed to clear query logs. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Query logs successfully cleared.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(QueryDetailDataTable $dataTable, string $logDate)
    {
        if (!file_exists(storage_path('logs/query-' . $logDate . '.log'))) {
            abort(404, 'Log file not found for the specified date.');
        }

        return $dataTable->render('pages.dashboard.log.query.show', $this->service->show($logDate));
    }
}
