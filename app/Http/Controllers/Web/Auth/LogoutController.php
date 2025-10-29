<?php

namespace App\Http\Controllers\Web\Auth;

use App\Facades\Toast;
use App\Foundations\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        Auth::logout();

        $session = $request->session();
        $session->invalidate();
        $session->regenerateToken();

        Toast::primary('Success', 'You have been logged out successfully.');

        return to_route('landing.home');
    }
}
