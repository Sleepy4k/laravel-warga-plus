<?php

namespace App\Services\Web\Dashboard\Log;

use App\Enums\ReportLogType;
use App\Foundations\Service;
use Illuminate\Support\Facades\Artisan;
use Spatie\Activitylog\Models\Activity;

class ModelService extends Service
{
    /**
     * The name of the log to be used.
     */
    private const LOG_NAME = 'model';

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $logCount = Activity::where('log_name', self::LOG_NAME)
            ->where('created_at', '<', now()->subDays(60))
            ->count();

        return compact('logCount');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     *
     * @return bool
     */
    public function store(mixed $data): bool
    {
        if (empty($data) || is_null($data)) {
            return false;
        }

        if (strtolower($data) !== self::LOG_NAME) {
            return false;
        }

        try {
            Artisan::call('activitylog:clean', [
                'log' => self::LOG_NAME,
                '--force' => true,
                '--days' => config('activitylog.delete_records_older_than_days', 60),
            ]);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to clear ' . self::LOG_NAME . ' logs: ' . $th->getMessage(), [
                'data' => $data,
            ]);
            return false;
        }
    }
}
