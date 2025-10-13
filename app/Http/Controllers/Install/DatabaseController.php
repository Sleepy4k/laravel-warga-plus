<?php

namespace App\Http\Controllers\Install;

use App\Foundations\Controller;
use App\Services\Install\DatabaseService;

class DatabaseController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private DatabaseService $service
    ) {}

    /**
     * Migrate the database
     */
    public function __invoke()
    {
        try {
            $this->service->invoke();

            return to_route('install.user');
        } catch (\Throwable $th) {
            return back()->withErrors([
                'database' => 'Failed to migrate the database. Please check your database connection settings and try again.',
            ]);
        }
    }
}
