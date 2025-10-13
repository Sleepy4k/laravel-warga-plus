<?php

namespace App\Http\Controllers\Install;

use App\Foundations\Controller;
use App\Services\Install\FinalizeService;

class FinalizeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private FinalizeService $service
    ) {}

    /**
     * Finalize the installation with redirect
     */
    public function __invoke()
    {
        try {
            return redirect($this->service->invoke());
        } catch (\Throwable $th) {
            return back()->withErrors([
                'finalize' => 'Failed to finalize the installation. Please try again.',
            ]);
        }
    }
}
