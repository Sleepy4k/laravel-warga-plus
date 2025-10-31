<?php

namespace App\Http\Controllers\Web\Auth;

use App\Actions\SendRequestResetPassword;
use App\Facades\Toast;
use App\Foundations\Controller;
use App\Http\Requests\Web\Auth\ForgotPasswordRequest;
use App\Http\Requests\Web\Auth\UpdatePasswordRequest;
use App\Models\User;
use App\Support\AttributeEncryptor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $phone = $request->validated()['phone'];
        $isValid = preg_match('/^8[1-9][0-9]{6,15}$/', $phone);

        if (!$isValid) {
            Toast::danger('Error', 'Invalid phone number.');
            return back();
        }

        $phone = AttributeEncryptor::encrypt($phone);

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            Toast::danger('Error', __('passwords.user'));
            return back();
        }

        try {
            DB::table('password_reset_tokens')->where('phone', $phone)->delete();

            $token = Str::random(64);

            DB::table('password_reset_tokens')->insert([
                'phone' => $phone,
                'token' => AttributeEncryptor::encrypt($token),
                'created_at' => now()
            ]);

            $status = app(SendRequestResetPassword::class)->execute($user, $token);

            if ($status) {
                Toast::primary('Success', __('passwords.sent'));
            } else {
                Toast::danger('Error', 'Failed to send WhatsApp message.');
            }
        } catch (\Throwable $e) {
            Toast::danger('Error', 'Failed to send WhatsApp message.');
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $token)
    {
        $phone = $request->query('phone');
        if (!$phone || !preg_match('/^8[1-9][0-9]{6,15}$/', $phone)) {
            Toast::danger('Error', 'Invalid phone number.');
            return back();
        }

        $phone = AttributeEncryptor::encrypt($phone);

        if (!User::where('phone', $phone)->exists()) {
            Toast::danger('Error', __('passwords.user'));
            return back();
        }

        $isTokenExists = DB::table('password_reset_tokens')
            ->where('phone', $phone)
            ->first();

        if (!$isTokenExists) {
            Toast::danger('Error', __('passwords.token'));
            return back();
        }

        $descryptedToken = AttributeEncryptor::decrypt($isTokenExists->token);

        if ($descryptedToken !== $token) {
            Toast::danger('Error', __('passwords.token'));
            return back();
        }

        $expiryMinutes = config('auth.passwords.users.expire', 60);
        $tokenCreationTime = $isTokenExists->created_at;
        if (Carbon::parse($tokenCreationTime)->addMinutes($expiryMinutes)->isPast()) {
            Toast::danger('Error', __('passwords.token'));
            return back();
        }

        Toast::primary('Info', 'Please enter your new password.');

        return view('pages.auth.reset-password', compact('token', 'phone'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePasswordRequest $request, string $token)
    {
        try {
            $credentials = $request->validated();
            $phone = $credentials['phone'];
            $password = $credentials['password'];

            $decryptedPhone = AttributeEncryptor::decrypt($phone);

            if (!$decryptedPhone || !preg_match('/^8[1-9][0-9]{6,15}$/', $decryptedPhone)) {
                Toast::danger('Error', 'Invalid phone number.');
                return back();
            }

            $user = User::where('phone', $phone)->first();

            if (!$user) {
                Toast::danger('Error', __('passwords.user'));
                return back();
            }

            $isTokenExists = DB::table('password_reset_tokens')
                ->where('phone', $phone)
                ->first();

            if (!$isTokenExists) {
                Toast::danger('Error', __('passwords.token'));
                return back();
            }

            $descryptedToken = AttributeEncryptor::decrypt($isTokenExists->token);

            if ($descryptedToken !== $token) {
                Toast::danger('Error', __('passwords.token'));
                return back();
            }

            $expiryMinutes = config('auth.passwords.users.expire', 60);
            $tokenCreationTime = $isTokenExists->created_at;
            if (Carbon::parse($tokenCreationTime)->addMinutes($expiryMinutes)->isPast()) {
                Toast::danger('Error', __('passwords.token'));
                return back();
            }

            $user->password = $password;
            $user->save();

            DB::table('password_reset_tokens')->where('phone', $phone)->delete();

            Toast::primary('Success', __('passwords.reset'));

            return redirect()->route('login');
        } catch (\Throwable $th) {
            dd($th);
            Toast::danger('Error', 'Failed to reset password.');
            return back();
        }
    }
}
