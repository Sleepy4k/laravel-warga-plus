<?php

namespace App\Http\Controllers\Web\Landing;

use App\Foundations\Controller;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('pages.landing.information');
    }
}
