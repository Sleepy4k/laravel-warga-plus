<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('getWebRoutes')) {
    /**
     * Get all web routes.
     *
     * @return array
     */
    function getWebRoutes(): array
    {
        return collect(Route::getRoutes())
            ->filter(function ($route) {
                return $route->getName() && in_array('GET', $route->methods())
                    && in_array('web', $route->middleware())
                    && !str_starts_with($route->getName(), 'sanctum.');
            })
            ->map(function ($route) {
                return $route->getName();
            })
            ->values()
            ->toArray();
    }
}
