<?php

namespace App\Services\Web\Dashboard\Log;

use App\Enums\LogReaderType;
use App\Enums\ReportLogType;
use App\Facades\LogReader;
use App\Foundations\Service;

class QueryService extends Service
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $fileThreshold = config('logging.delete_files_older_than_days', 7);
        $thresholdDate = now()->subDays($fileThreshold)->toDateString();

        $fileList = LogReader::getFileList(LogReaderType::DAILY, 'query');

        $filteredFiles = array_filter($fileList, function ($file) use ($thresholdDate) {
            $fileDate = substr($file['name'], 6, 10);
            return $fileDate < $thresholdDate;
        });

        $logCount = count($filteredFiles);

        return compact('logCount');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function store(mixed $data): bool
    {
        if (empty($data) || is_null($data)) {
            return false;
        }

        if (strtolower($data) !== 'query') {
            return false;
        }

        try {
            $fileThreshold = config('logging.delete_files_older_than_days', 7);
            $thresholdDate = now()->subDays($fileThreshold)->toDateString();

            $fileList = LogReader::getFileList(LogReaderType::DAILY, 'query');
            $filteredFiles = array_filter($fileList, function ($file) use ($thresholdDate) {
                $fileDate = substr($file['name'], 6, 10);
                return $fileDate < $thresholdDate;
            });

            foreach ($filteredFiles as $file) {
                $filePath = storage_path('logs/' . $file['name']);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to clear query logs: ' . $th->getMessage(), [
                'data' => $data,
            ]);
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $logDate
     *
     * @return array
     */
    public function show(string $logDate): array
    {
        return [];
    }
}
