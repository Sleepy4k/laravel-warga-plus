<?php

namespace App\Http\Controllers\Web\Auth;

use App\Facades\Toast;
use App\Foundations\Controller;
use App\Http\Requests\Web\Auth\ForgotPasswordRequest;
use App\Http\Requests\Web\Auth\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.auth.forgot-password');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ForgotPasswordRequest $request)
    {
        $phone = !preg_match('/^8[1-9][0-9]{6,10}$/', $request->validated()['phone']);

        if (!$phone) {
            Toast::danger('Error', 'Invalid phone number.');
            return back();
        }

        if (!User::where('phone', $phone)->exists()) {
            Toast::danger('Error', __('passwords.user'));
            return back();
        }

        switch (Password::sendResetLink(['phone' => $phone])) {
        case Password::RESET_LINK_SENT:
            Toast::primary('Success', __('passwords.sent'));
            break;
        case Password::INVALID_USER:
            Toast::danger('Error', __('passwords.user'));
            break;
        case Password::RESET_THROTTLED:
            Toast::danger('Error', __('passwords.throttled'));
            break;
        case Password::INVALID_TOKEN:
            Toast::danger('Error', __('passwords.token'));
            break;
        default:
            Toast::danger('Error', __('passwords.user'));
            break;
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $token)
    {
        $phone = $request->query('phone');
        if (!$phone || !preg_match('/^8[1-9][0-9]{6,10}$/', $phone)) {
            Toast::danger('Error', 'Invalid phone number.');
            return back();
        }

        if (!User::where('phone', $phone)->exists()) {
            Toast::danger('Error', __('passwords.user'));
            return back();
        }

        if (!Password::tokenExists(User::where('phone', $phone)->first(), $token)) {
            Toast::danger('Error', __('passwords.token'));
            return back();
        }

        Toast::info('Info', 'Please enter your new password.');

        return view('pages.auth.reset-password', compact('token', 'phone'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePasswordRequest $request, string $token)
    {
        $credentials = $request->validated();
        $status = Password::reset(
            array_merge($credentials, ['token' => $token]),
            function ($user) use ($credentials) {
                $user->password = bcrypt($credentials['password']);
                $user->save();

                event(new PasswordReset($user));
            }
        );

        switch ($status) {
        case Password::PASSWORD_RESET:
            Toast::primary('Success', __('passwords.reset'));
            return to_route('login');
        case Password::INVALID_TOKEN:
            Toast::danger('Error', __('passwords.token'));
            return back()->withErrors(['phone' => __('passwords.token')]);
        case Password::INVALID_USER:
            Toast::danger('Error', __('passwords.user'));
            return back()->withErrors(['phone' => __('passwords.user')]);
        case Password::RESET_THROTTLED:
            Toast::danger('Error', __('passwords.throttled'));
            return back()->withErrors(['phone' => __('passwords.throttled')]);
        default:
            Toast::danger('Error', __('passwords.user'));
            return back()->withErrors(['phone' => __('passwords.user')]);
        }
    }
}
