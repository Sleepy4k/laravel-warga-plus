<?php

namespace App\Http\Controllers\Web\Landing;

use App\Foundations\Controller;
use App\Services\Web\Landing\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ReportService $service, Request $request)
    {
        $queryParams = $request->query();

        $params = [];
        if (isset($queryParams['q']) && !empty($queryParams['q'])) {
            $params['search'] = $queryParams['q'];
        }

        if (isset($queryParams['type']) && !empty($queryParams['type'])) {
            $params['type'] = $queryParams['type'];
        }

        if (isset($queryParams['status']) && !empty($queryParams['status'])) {
            $params['status'] = $queryParams['status'];
        }

        return view('pages.landing.report', $service->invoke($params));
    }
}
