<?php

namespace App\Http\Controllers\Web\Dashboard\Misc;

use App\DataTables\Misc\JobDataTable;
use App\Foundations\Controller;
use App\Policies\Web\Misc\JobPolicy;
use App\Services\Web\Dashboard\Misc\JobService;
use App\Traits\Authorizable;

class JobController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private JobService $service,
        private $policy = JobPolicy::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(JobDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.misc.jobs.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        if (!$this->service->store()) {
            return $this->sendResponse(null, 'Failed to clear jobs.', 500);
        }

        return $this->sendResponse(null, 'Jobs cleared successfully.', 200);
    }
}
