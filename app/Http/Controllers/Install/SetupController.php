<?php

namespace App\Http\Controllers\Install;

use App\Foundations\Controller;
use App\Http\Requests\Install\SetupStoreRequest;
use App\Policies\Install\SetupPolicy;
use App\Services\Install\SetupService;
use App\Support\InstallationStep;
use Illuminate\Support\Facades\Gate;

class SetupController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private SetupService $service,
        private InstallationStep $installationStep = new InstallationStep('setup')
    ) {}

    /**
     * Application setup
     */
    public function index()
    {
        $gate = Gate::inspect('viewAny', [SetupPolicy::class, $this->installationStep->isPreviousStepCompleted()]);
        if (!$gate->allowed()) return to_route('install.permissions');

        try {
            $this->installationStep->markAsCompleted();

            return view('pages.install.setup', $this->service->index());
        } catch (\Throwable $th) {
            $this->installationStep->markAsNotCompleted();
            return back()->withErrors([
                'setup' => 'Failed to load setup page. Please check your server configuration and try again.',
            ]);
        }
    }

    /**
     * Store the environmental variables
     *
     * @param SetupStoreRequest $request
     */
    public function store(SetupStoreRequest $request)
    {
        try {
            $this->service->store($request->validated());

            return to_route('install.database');
        } catch (\Throwable $th) {
            return back()->withErrors([
                'setup' => 'Failed to store setup data. Please try again.',
            ]);
        }
    }
}
