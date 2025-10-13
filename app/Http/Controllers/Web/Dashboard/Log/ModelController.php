<?php

namespace App\Http\Controllers\Web\Dashboard\Log;

use App\DataTables\Log\ModelDataTable;
use App\Foundations\Controller;
use App\Policies\Web\Log\ModelPolicy;
use App\Services\Web\Dashboard\Log\ModelService;
use App\Traits\Authorizable;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ModelService $service,
        private $policy = ModelPolicy::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(ModelDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.log.model.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->service->store($request->get('log_name'))) {
            return $this->sendResponse(null, 'Failed to clear model logs. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Model logs successfully cleared.', 201);
    }
}
