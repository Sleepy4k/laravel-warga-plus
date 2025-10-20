<?php

namespace App\Http\Controllers\Install;

use App\Foundations\Controller;
use App\Policies\Install\PermissionPolicy;
use App\Services\Install\PermissionService;
use App\Support\InstallationStep;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private PermissionService $service,
        private InstallationStep $installationStep = new InstallationStep('permissions')
    ) {}

    /**
     * Shows the permissions page
     */
    public function __invoke()
    {
        $gate = Gate::inspect('viewAny', [PermissionPolicy::class, $this->installationStep->isPreviousStepCompleted()]);
        if (!$gate->allowed()) return to_route('install.requirements');

        try {
            $this->installationStep->markAsCompleted();

            return view('pages.install.permissions', $this->service->invoke());
        } catch (\Throwable $th) {
            $this->installationStep->markAsNotCompleted();
            return to_route('install.requirements')->withErrors([
                'permissions' => 'Failed to load permissions. Please check your server configuration and try again.',
            ]);
        }
    }
}
