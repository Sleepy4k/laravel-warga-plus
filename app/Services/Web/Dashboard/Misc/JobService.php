<?php

namespace App\Services\Web\Dashboard\Misc;

use App\Enums\ReportLogType;
use App\Foundations\Service;
use Illuminate\Support\Facades\DB;

class JobService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $thresholdDate = now()->subDays(30);
        $totalJobs = DB::table('jobs')->where('created_at', '<=', $thresholdDate)->count();
        $failedJobs = DB::table('failed_jobs')->where('failed_at', '<=', $thresholdDate)->count();
        $jobCount = $totalJobs + $failedJobs;

        return compact('jobCount');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): bool
    {
        try {
            $thresholdDate = now()->subDays(30);
            DB::table('jobs')->where('created_at', '<=', $thresholdDate)->delete();
            DB::table('failed_jobs')->where('created_at', '<=', $thresholdDate)->delete();

            return true;
        } catch (\Exception $e) {
            $this->sendReportLog(ReportLogType::ERROR, 'Error during job clearing operation', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
