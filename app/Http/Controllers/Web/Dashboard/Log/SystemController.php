<?php

namespace App\Http\Controllers\Web\Dashboard\Log;

use App\DataTables\Log\SystemDataTable;
use App\DataTables\Log\SystemDetailDataTable;
use App\Foundations\Controller;
use App\Policies\Web\Log\SystemPolicy;
use App\Services\Web\Dashboard\Log\SystemService;
use App\Traits\Authorizable;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private SystemService $service,
        private $policy = SystemPolicy::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
            'show' => 'view',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(SystemDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.log.system.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->service->store($request->get('log_name'))) {
            return $this->sendResponse(null, 'Failed to clear auth logs. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Auth logs successfully cleared.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SystemDetailDataTable $dataTable, string $logDate)
    {
        if (!file_exists(storage_path('logs/laravel-' . $logDate . '.log'))) {
            abort(404, 'Log file not found for the specified date.');
        }

        return $dataTable->render('pages.dashboard.log.system.show', $this->service->show($logDate));
    }
}
