<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Foundations\Controller;
use App\Services\Web\Dashboard\AnalyticService;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, AnalyticService $service)
    {
        return view('pages.dashboard.home', $service->invoke());
    }
}
