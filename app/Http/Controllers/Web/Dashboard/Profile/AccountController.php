<?php

namespace App\Http\Controllers\Web\Dashboard\Profile;

use App\Foundations\Controller;
use App\Services\Web\Dashboard\Profile\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AccountService $service, Request $request)
    {
        return view('pages.dashboard.profile.account', $service->index());
    }
}
