<?php

namespace App\Http\Controllers\Install;

use App\Foundations\Controller;
use App\Policies\Install\FinishPolicy;
use App\Services\Install\FinishedService;
use App\Support\InstallationStep;
use Illuminate\Support\Facades\Gate;

class FinishedController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private FinishedService $service,
        private InstallationStep $installationStep = new InstallationStep('finish')
    ) {}

    /**
     * Display the finish step or apply patches
     */
    public function __invoke()
    {
        $gate = Gate::inspect('viewAny', [FinishPolicy::class, $this->installationStep->isPreviousStepCompleted()]);
        if (!$gate->allowed()) return to_route('install.user');

        try {
            $this->installationStep->markAsCompleted();

            return view('pages.install.finish', $this->service->invoke());
        } catch (\Throwable $th) {
            $this->installationStep->markAsNotCompleted();
            return back()->withErrors([
                'finish' => 'Failed to complete the installation. Please try again.',
            ]);
        }
    }
}
