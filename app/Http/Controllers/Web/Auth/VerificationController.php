<?php

namespace App\Http\Controllers\Web\Auth;

use App\Actions\SendEmailVerification;
use App\Facades\Toast;
use App\Foundations\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class VerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth('web')->user();
        $key = 'send-verification-email:' . $user->id;
        return view('pages.auth.verification', [
            'reset_at' => RateLimiter::availableIn($key),
            'remaining' => RateLimiter::remaining($key, 1),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SendEmailVerification $action, Request $request)
    {
        $user = $request->user('web');
        if (!$user || is_null($user)) {
            Toast::error('System', 'You must be logged in to request a verification link.');
            return to_route('login');
        }

        if (RateLimiter::tooManyAttempts('send-verification-email:' . $user->id, 1)) {
            Toast::error('System', 'You have exceeded the maximum number of verification link requests. Please try again later.');
            return back();
        }

        RateLimiter::hit('send-verification-email:' . $user->id, 180);

        if (!$action->execute($user)) {
            Toast::error('System', 'Failed to send verification link. Please try again later.');
            return back();
        }

        Toast::info('System', 'A new verification link has been sent to your email address.');

        return back()->with('status', 'Verification link sent to your email address.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function show(EmailVerificationRequest $request)
    {
        $request->fulfill();

        Toast::primary('System', 'Email address verified successfully.');

        return to_route('dashboard.index');
    }
}
