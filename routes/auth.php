<?php

use App\Http\Controllers\Web\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Unauthenticated Route
    |--------------------------------------------------------------------------
    |
    | You can list public endpoint for any user in here. These routes are meant
    | to be used for guests and are not guarded by any authentication system.
    | Remember not to list anything of importance, use authenticate route instead.
    */

    Route::middleware('guest')->group(function () {
        Route::resource('login', Auth\LoginController::class)
            ->only(['index', 'store'])
            ->name('index', 'login');

        Route::resource('register', Auth\RegisterController::class)
            ->only(['index', 'store'])
            ->name('index', 'register');

        Route::controller(Auth\ForgotController::class)->group(function () {
            Route::get('forgot-password', 'index')->name('password.request');
            Route::post('forgot-password', 'store')->name('password.email');
            Route::get('reset-password/{token}', 'show')->name('password.reset');
            Route::post('reset-password/{token}', 'update')->name('password.update');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Authenticated Route
    |--------------------------------------------------------------------------
    |
    | In here you can list any route for authenticated user. These routes
    | are meant to be used privately since the access is exclusive to authenticated
    | user who had obtained their access through the login process.
    */

    Route::middleware('auth')->group(function () {
        Route::prefix('register')->name('register.')->group(function () {
            Route::resource('complete', Auth\CustomRegistrationController::class)
                ->only(['index', 'store'])
                ->name('index', 'complete');
        });

        Route::middleware('prevent_invalid_registration')->group(function () {
            Route::middleware('verified')->post('logout', Auth\LogoutController::class)->name('logout');

            Route::middleware('verification_email_access')->prefix('account')->group(function () {
                Route::controller(Auth\VerificationController::class)->group(function () {
                    Route::get('verify', 'index')->name('verification.notice');
                    Route::get('verify/{id}/{hash}', 'show')->middleware('signed')->name('verification.verify');
                    Route::post('verification-notification', 'store')->name('verification.send');
                });
            });
        });
    });
});
