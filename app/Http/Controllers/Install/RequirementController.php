<?php

namespace App\Http\Controllers\Install;

use App\Foundations\Controller;
use App\Policies\Install\RequirementPolicy;
use App\Services\Install\RequirementService;
use App\Support\InstallationStep;
use Illuminate\Support\Facades\Gate;

class RequirementController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private RequirementService $service,
        private InstallationStep $installationStep = new InstallationStep('requirements')
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function __invoke()
    {
        Gate::authorize('viewAny', RequirementPolicy::class);

        try {
            $this->installationStep->markAsCompleted();

            return view('pages.install.requirements', $this->service->invoke());
        } catch (\Throwable $th) {
            $this->installationStep->markAsNotCompleted();
            return back()->withErrors([
                'requirements' => 'Failed to load requirements. Please check your server configuration and try again.',
            ]);
        }
    }
}
