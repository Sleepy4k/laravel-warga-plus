<?php

namespace App\Http\Controllers\Web\Dashboard\Setting;

use App\Facades\Toast;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Setting\ApplicationRequest;
use App\Http\Requests\Web\Dashboard\Setting\MaintenanceRequest;
use App\Models\Setting;
use App\Policies\Web\Setting\ApplicationPolicy;
use App\Services\Web\Dashboard\Setting\ApplicationService;
use App\Traits\Authorizable;

class ApplicationController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ApplicationService $service,
        private $policy = ApplicationPolicy::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
            'update' => 'update',
            'destroy' => 'update'
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.settings.application.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MaintenanceRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            Toast::error('Something went wrong', 'Failed to enable maintenance mode');
            return back()->withInput();
        }

        Toast::primary('Success', 'Maintenance mode enabled successfully');

        return to_route('dashboard.settings.application.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ApplicationRequest $request, string $appSettingType)
    {
        $data = $request->validated();
        $previousTab = $data['tab'] ?? null;
        unset($data['tab']);

        if (!$this->service->update($data, $appSettingType)) {
            Toast::error('Something went wrong', 'Failed to update application settings');
            return back()->withInput();
        }

        Toast::primary('Success', 'Application settings updated successfully');

        return to_route('dashboard.settings.application.index', ['tab' => $previousTab]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $settingKey)
    {
        if (!$this->service->destroy($settingKey)) {
            return $this->sendResponse(null, 'Failed to delete setting. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Setting successfully deleted.', 200);
    }
}
