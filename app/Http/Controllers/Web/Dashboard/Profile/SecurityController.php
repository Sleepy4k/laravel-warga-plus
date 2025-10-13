<?php

namespace App\Http\Controllers\Web\Dashboard\Profile;

use App\Facades\Toast;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Profile\SecurityRequest;
use App\Services\Web\Dashboard\Profile\SecurityService;

class SecurityController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private SecurityService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.profile.security', $this->service->index());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SecurityRequest $request)
    {
        if (!$this->service->update($request->validated())) {
            Toast::error('Something went wrong', 'Failed to update security settings. Please try again.');
            return back();
        }

        Toast::primary('Success', 'Security settings updated successfully.');
        return to_route('profile.security.index');
    }
}
