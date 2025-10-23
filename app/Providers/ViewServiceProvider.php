<?php

namespace App\Providers;

use App\View\Composers\BreadcrumbComposer;
use App\View\Composers\SettingComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        View::composer([
            'components.layouts.auth',
            'components.layouts.landing',
            'components.layouts.dashboard',
            'components.layouts.error',

            'components.auth.login.header',

            'components.dashboard.sidebar',
            'components.dashboard.footer',

            'components.landing.navbar',
            'components.landing.footer',
        ], SettingComposer::class);

        View::composer('components.dashboard.breadcrumb', BreadcrumbComposer::class);
    }
}
