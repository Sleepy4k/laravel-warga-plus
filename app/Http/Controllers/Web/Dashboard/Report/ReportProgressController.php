<?php

namespace App\Http\Controllers\Web\Dashboard\Report;

use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Report\Progress\StoreRequest;
use App\Models\Report;
use App\Services\Web\Dashboard\Report\ReportProgressService;
use App\Traits\Authorizable;

class ReportProgressController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ReportProgressService $service,
        private $policy = Report::class,
        private $abilities = [
            '__invoke' => 'store',
        ]
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreRequest $request)
    {
        if (!$this->service->invoke($request->validated())) {
            return $this->sendResponse(null, 'Failed to add report progress. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Report progress successfully added.', 201);
    }
}
