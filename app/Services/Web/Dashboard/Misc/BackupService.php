<?php

namespace App\Services\Web\Dashboard\Misc;

use App\Enums\ReportLogType;
use App\Foundations\Service;
use Illuminate\Support\Facades\Artisan;

class BackupService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $fileList = glob(storage_path('backups/backup-*.sql')) ?: [];
        $fileList = array_filter($fileList, function ($file) {
            return filemtime($file) <= strtotime('-30 days');
        });
        $backupCount = count($fileList);

        return compact('backupCount');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        try {
            Artisan::call('naka:backup-db', [
                '--type' => $request['type'],
            ]);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Error during backup operation', [
                'type' => $request['type'],
                'error' => $th->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $date): array
    {
        $filePath = storage_path("backups/backup-{$date}.sql");
        if (!file_exists($filePath)) {
            return [];
        }

        return [
            'file' => file_get_contents($filePath),
            'name' => basename($filePath),
            'type' => 'application/sql',
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $date): bool
    {
        $filePath = storage_path("backups/backup-{$date}.sql");
        if (!file_exists($filePath)) {
            return false;
        }

        try {
            unlink($filePath);
            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Error during backup file deletion', [
                'date' => $date,
                'error' => $th->getMessage(),
            ]);
            return false;
        }
    }
}
