<?php

namespace App\Providers;

use App\Enums\ApplicationSettingType;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
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

        Route::pattern('logDate', '[0-9]{4}-[0-9]{2}-[0-9]{2}');
        Route::pattern('appSettingType', implode('|', ApplicationSettingType::toArray()));

        RateLimiter::for('web', function ($request) {
            return Limit::perMinute(60)
                ->by(optional($request->user())->id ?: $request->ip())
                ->response(function (Request $request) {
                    if ($request->wantsJson() || $request->expectsJson()) {
                        return response()->json([
                            'message' => 'Too Many Attempts.',
                        ], 429);
                    }

                    return abort(429, 'Too Many Attempts.');
                });
        });

        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60)->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Too Many Attempts.',
                        'data' => [
                            'limit' => $headers['X-RateLimit-Limit'],
                            'remaining' => $headers['X-RateLimit-Remaining'],
                            'reset' => $headers['X-RateLimit-Reset'],
                        ]
                    ], 429);
                });
        });
    }
}
