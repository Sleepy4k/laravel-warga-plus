<?php

namespace App\Http\Controllers\Web\Dashboard\Log;

use App\DataTables\Log\CacheDataTable;
use App\DataTables\Log\CacheDetailDataTable;
use App\Foundations\Controller;
use App\Policies\Web\Log\CachePolicy;
use App\Services\Web\Dashboard\Log\CacheService;
use App\Traits\Authorizable;
use Illuminate\Http\Request;

class CacheController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private CacheService $service,
        private $policy = CachePolicy::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
            'show' => 'view',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(CacheDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.log.cache.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->service->store($request->get('log_name'))) {
            return $this->sendResponse(null, 'Failed to clear cache logs. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Cache logs successfully cleared.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CacheDetailDataTable $dataTable, string $logDate)
    {
        if (!file_exists(storage_path('logs/cache-' . $logDate . '.log'))) {
            abort(404, 'Log file not found for the specified date.');
        }

        return $dataTable->render('pages.dashboard.log.cache.show', $this->service->show($logDate));
    }
}
