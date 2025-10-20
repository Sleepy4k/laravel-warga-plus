<?php

use App\Http\Controllers\Web\Auth;
use App\Http\Controllers\Web\Policy;
use App\Http\Controllers\Web\Storage;
use App\Http\Controllers\Web\Landing;
use App\Http\Controllers\Web\Dashboard\AnalyticController;
use App\Http\Controllers\Web\Dashboard\Profile;
use App\Http\Controllers\Web\Dashboard\User;
use App\Http\Controllers\Web\Dashboard\RBAC;
use App\Http\Controllers\Web\Dashboard\Log;
use App\Http\Controllers\Web\Dashboard\Misc;
use App\Http\Controllers\Web\Dashboard\Menu;
use App\Http\Controllers\Web\Dashboard\Setting;
use App\Http\Controllers\Web\Dashboard\Administration\Agenda;
use App\Http\Controllers\Web\Dashboard\Administration\Document;
use App\Http\Controllers\Web\Dashboard\Administration\Reference;
use App\Http\Controllers\Web\Dashboard\Administration\Transaction;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
|
| You can list public endpoint for any user in here. These routes are not guarded
| by any authentication system. In other words, any user can access it directly.
| Remember not to list anything of importance, use authenticate route instead.
*/

Route::get('/', Landing\HomeController::class)->name('landing.home');

Route::middleware('throttle:10,1')->group(function () {
    Route::get('/cookie-policy', Policy\CookieController::class)->name('cookie.policy');
    Route::get('/privacy-policy', Policy\PrivacyController::class)->name('privacy.policy');
    Route::get('/terms-of-service', Policy\ToSController::class)->name('tos.policy');
});

Route::get('storage/{path}', Storage\ServeController::class)
    ->where('path', '.*')
    ->middleware('throttle:15,1');

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
        Route::middleware('verification_email_access')->prefix('email')->group(function () {
            Route::controller(Auth\VerificationController::class)->group(function () {
                Route::get('verify', 'index')->name('verification.notice');
                Route::get('verify/{id}/{hash}', 'show')->middleware('signed')->name('verification.verify');
                Route::post('verification-notification', 'store')->name('verification.send');
            });
        });

        Route::middleware('verified')->group(function () {
            Route::post('logout', Auth\LogoutController::class)->name('logout');

            Route::prefix('profile')->name('profile.')->group(function () {
                Route::post('heartbeat', Profile\HeartbeatController::class)->name('heartbeat');
                Route::get('account', Profile\AccountController::class)->name('account.index');

                Route::resource('shortcut', Profile\ShortcutController::class)->except(['create', 'show', 'edit']);

                Route::controller(Profile\SettingController::class)->group(function () {
                    Route::get('setting', 'index')->name('setting.index');
                    Route::put('setting', 'update')->name('setting.update');
                    Route::delete('setting', 'destroy')->name('setting.destroy');
                });

                Route::controller(Profile\SecurityController::class)->group(function () {
                    Route::get('security', 'index')->name('security.index');
                    Route::put('security', 'update')->name('security.update');
                });
            });

            Route::name('dashboard.')->prefix('dashboard')->group(function () {
                Route::get('/', AnalyticController::class)->name('index');

                Route::resource('user', User\ListController::class)
                    ->except(['create', 'edit']);

                Route::prefix('rbac')->name('rbac.')->group(function () {
                    Route::resource('role', RBAC\RoleController::class)
                        ->except(['create', 'show', 'edit']);
                    Route::resource('permission', RBAC\PermissionController::class)
                        ->except(['create', 'show', 'edit']);
                });

                Route::prefix('log')->name('log.')->group(function () {
                    Route::resource('auth', Log\AuthController::class)->only(['index', 'store']);
                    Route::resource('model', Log\ModelController::class)->only(['index', 'store']);
                    Route::resource('system', Log\SystemController::class)
                        ->only(['index', 'store', 'show'])
                        ->parameters(['system' => 'logDate']);
                    Route::resource('query', Log\QueryController::class)
                        ->only(['index', 'store', 'show'])
                        ->parameters(['query' => 'logDate']);
                    Route::resource('cache', Log\CacheController::class)
                        ->only(['index', 'store', 'show'])
                        ->parameters(['cache' => 'logDate']);
                });

                Route::resource('document', Document\DocumentController::class)
                    ->except(['create', 'show', 'edit']);

                Route::prefix('document')->name('document.')->group(function () {
                    Route::get('{document}/files', [Document\DocumentController::class, 'show'])
                        ->name('show');

                    Route::resource('category', Document\DocumentCategoryController::class)
                        ->except(['create', 'show', 'edit']);

                    Route::controller(Document\DocumentVersionController::class)->group(function () {
                        Route::prefix('version')->name('version.')->group(function () {
                            Route::post('{document}', 'store')->name('store');
                            Route::delete('{document}/{version}', 'destroy')->name('destroy');
                            Route::get('{document}/{version}/download', 'show')->name('show');
                        });
                    });

                    Route::controller(Document\DocumentGalleryController::class)->group(function () {
                        Route::prefix('gallery')->name('gallery.')->group(function () {
                            Route::get('/', 'index')->name('index');
                            Route::get('{document}/{version}/download', 'show')->name('show');
                        });
                    });
                });

                Route::prefix('letter')->name('letter.')->group(function () {
                    Route::prefix('transaction')->name('transaction.')->group(function () {
                        Route::resource('incoming', Transaction\IncomingController::class)
                            ->parameters(['incoming' => 'letter'])
                            ->except(['create', 'show', 'edit']);
                        Route::resource('outgoing', Transaction\OutgoingController::class)
                            ->parameters(['outgoing' => 'letter'])
                            ->except(['create', 'show', 'edit']);
                        Route::resource('{letter}/disposition', Transaction\DispositionController::class)
                            ->except(['create', 'show', 'edit']);
                        Route::resource('attachment', Transaction\AttachmentController::class)
                            ->only('destroy');
                    });

                    Route::prefix('agenda')->name('agenda.')->group(function () {
                        Route::get('incoming', Agenda\IncomingController::class)
                            ->name('incoming.index');
                        Route::get('outgoing', Agenda\OutgoingController::class)
                            ->name('outgoing.index');
                    });

                    Route::prefix('reference')->name('reference.')->group(function () {
                        Route::resource('classification', Reference\ClassificationController::class)->except(['create', 'show', 'edit']);
                        Route::resource('status', Reference\StatusController::class)->except(['create', 'show', 'edit']);
                    });
                });

                Route::prefix('settings')->name('settings.')->group(function () {
                    Route::delete('application/{settingKey}', [Setting\ApplicationController::class, 'destroy'])
                        ->name('application.destroy');

                    Route::resource('application', Setting\ApplicationController::class)
                        ->only(['index', 'store', 'update'])
                        ->parameters(['application' => 'appSettingType']);
                    Route::resource('uploader', Setting\UploaderController::class)
                        ->only(['index', 'store']);
                });

                Route::prefix('misc')->name('misc.')->group(function () {
                    Route::resource('backup', Misc\BackupController::class)
                        ->except(['create', 'update', 'edit']);
                    Route::resource('sitemap', Misc\SitemapController::class)
                        ->except(['show', 'edit'])
                        ->parameters(['sitemap' => 'sitemapData']);
                    Route::resource('job', Misc\JobController::class)
                        ->only(['index', 'store']);
                });

                Route::prefix('menu')->name('menu.')->group(function () {
                    Route::resource('sidebar', Menu\SidebarController::class)
                        ->except(['create', 'show', 'edit']);
                    Route::resource('navbar', Menu\NavbarController::class)
                        ->except(['create', 'show', 'edit']);
                    Route::resource('shortcut', Menu\ShortcutController::class)
                        ->except(['create', 'show', 'edit']);

                    Route::prefix('save-order')->group(function () {
                        Route::post('sidebar', [Menu\SidebarController::class, 'saveOrder'])
                            ->name('sidebar.saveOrder');
                        Route::post('navbar', [Menu\NavbarController::class, 'saveOrder'])
                            ->name('navbar.saveOrder');
                    });
                });
            });
        });
    });
});
