<?php

namespace App\Http\Controllers\Web\Landing;

use App\Foundations\Controller;
use App\Services\Web\Landing\InformationService;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(InformationService $service, Request $request)
    {
        return view('pages.landing.information', $service->invoke());
    }
}
