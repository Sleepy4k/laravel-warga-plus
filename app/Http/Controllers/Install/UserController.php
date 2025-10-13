<?php

namespace App\Http\Controllers\Install;

use App\Foundations\Controller;
use App\Http\Requests\Install\UserStoreRequest;
use App\Policies\Install\UserPolicy;
use App\Services\Install\UserService;
use App\Support\InstallationStep;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private UserService $service,
        private InstallationStep $installationStep = new InstallationStep('user')
    ) {}

    /**
     * Display the user step
     */
    public function index()
    {
        $gate = Gate::inspect('viewAny', [UserPolicy::class, $this->installationStep->isPreviousStepCompleted()]);
        if (!$gate->allowed()) return to_route('install.setup');

        try {
            $this->installationStep->markAsCompleted();

            return view('pages.install.user', $this->service->index());
        } catch (\Throwable $th) {
            $this->installationStep->markAsNotCompleted();
            return back()->withErrors([
                'user' => 'Failed to load user setup. Please check your server configuration and try again.',
            ]);
        }
    }

    /**
     * Store the user
     *
     * @param UserStoreRequest $request
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $this->service->store($request->validated());

            return to_route('install.finalize');
        } catch (\Throwable $th) {
            return back()->withErrors([
                'user' => 'Failed to store user data. Please try again.',
            ]);
        }
    }
}
