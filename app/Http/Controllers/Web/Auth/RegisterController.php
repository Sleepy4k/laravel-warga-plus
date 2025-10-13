<?php

namespace App\Http\Controllers\Web\Auth;

use App\Foundations\Controller;
use App\Http\Requests\Web\Auth\RegisterRequest;
use App\Services\Web\Auth\RegisterService;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private RegisterService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.auth.register', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return back()->withInput();
        }

        return to_route('verification.notice');
    }
}
