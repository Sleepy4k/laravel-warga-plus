<?php

namespace App\Http\Controllers\Web\Dashboard\Log;

use App\DataTables\Log\AuthDataTable;
use App\Foundations\Controller;
use App\Policies\Web\Log\AuthPolicy;
use App\Services\Web\Dashboard\Log\AuthService;
use App\Traits\Authorizable;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private AuthService $service,
        private $policy = AuthPolicy::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(AuthDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.log.auth.index', $this->service->index());
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
}
