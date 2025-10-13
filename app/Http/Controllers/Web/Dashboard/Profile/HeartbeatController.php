<?php

namespace App\Http\Controllers\Web\Dashboard\Profile;

use App\Foundations\Controller;
use App\Services\Web\Dashboard\Profile\HeartbeatService;
use Illuminate\Http\Request;

class HeartbeatController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, HeartbeatService $heartbeatService)
    {
        $heartbeatService->invoke($request);

        return $this->sendResponse([
            'is_logged_in' => true,
            'last_seen' => auth('web')->user()->last_seen_status->value ?? 'offline',
        ], 'Heartbeat updated successfully.', 200);
    }
}
