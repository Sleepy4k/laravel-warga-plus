<?php

namespace App\Http\Controllers\Web\Auth;

use App\Foundations\Controller;
use App\Http\Requests\Web\Auth\CustomRegistrationRequest;
use App\Policies\Web\Auth\CustomRegisterPolicy;
use App\Services\Web\Auth\CustomRegistrationService;
use App\Traits\Authorizable;

class CustomRegistrationController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private CustomRegistrationService $service,
        private $policy = CustomRegisterPolicy::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.auth.custom-register', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomRegistrationRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return back()->withInput();
        }

        return to_route('verification.notice');
    }
}
