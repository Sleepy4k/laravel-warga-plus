<?php

namespace App\Services\Web\Dashboard\Report;

use App\Foundations\Service;
use App\Models\ReportProgress;

class ReportProgressService extends Service
{
    /**
     * Handle the incoming request.
     */
    public function invoke(array $request): bool
    {
        try {
            ReportProgress::create($request);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
