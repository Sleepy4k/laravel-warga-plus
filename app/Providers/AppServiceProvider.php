<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        JsonResource::withoutWrapping();

        if (file_exists(storage_path('.installed'))) {
            $settings = Setting::select('group', 'key', 'value')
                ->where('group', 'app')
                ->where('key', 'timezone')
                ->first();

            if ($settings) {
                config()->set('app.timezone', $settings->value);
                date_default_timezone_set($settings->value);
            }
        }
    }
}
