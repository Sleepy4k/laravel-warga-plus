<?php

namespace App\Http\Controllers\Web\Auth;

use App\Facades\Toast;
use App\Foundations\Controller;
use App\Http\Requests\Web\Auth\LoginRequest;
use App\Services\Web\Auth\LoginService;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private LoginService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.auth.login', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LoginRequest $request)
    {
        $userIp = request()->ip();
        $key = 'login-' . $userIp;

        if (RateLimiter::tooManyAttempts($key, config('auth.defaults.max_attempts'))) {
            Toast::danger('Error', 'Too many login attempts. Please try again later.');
            return back();
        }

        RateLimiter::hit($key, config('auth.defaults.throttle_seconds'));

        $user = $this->service->store($request->validated());

        if (!$user) {
            Toast::danger('Error', 'Invalid credentials provided.');
            return back()->withErrors([
                'phone-identity' => 'The provided credentials do not match our records.',
                'password' => 'The provided credentials do not match our records.',
            ]);
        }

        if (!$user->personal()->exists()) {
            Toast::primary('Info', 'Please complete your personal data.');
            return to_route('register.complete', [
                'payload' => encrypt($user->id . '|' . $user->identity_number)
            ]);
        }

        Toast::primary('Success', 'You have successfully logged in.');

        return redirect()->intended(route('dashboard.index', absolute: false));
    }
}
