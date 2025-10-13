<?php

namespace App\Http\Controllers\Web\Policy;

use App\Foundations\Controller;
use Illuminate\Http\Request;

class ToSController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('pages.policy.tos');
    }
}
