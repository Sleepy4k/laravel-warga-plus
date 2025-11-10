<?php

namespace App\Http\Controllers\Web\Landing;

use App\Foundations\Controller;
use App\Services\Web\Landing\HomeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(HomeService $service, Request $request)
    {
        return view('pages.landing.home', $service->invoke());
    }
}
