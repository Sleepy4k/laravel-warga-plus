<?php

namespace App\Http\Controllers\Web\Landing;

use App\Foundations\Controller;
use App\Models\Report;
use App\Services\Web\Landing\ReportDetailService;
use Illuminate\Http\Request;

class ReportDetailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Report $report, ReportDetailService $service, Request $request)
    {
        return view('pages.landing.detail-report', $service->invoke($report));
    }
}
