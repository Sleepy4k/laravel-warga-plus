<?php

namespace App\Services\Web\Dashboard\Administration\Reference;

use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\LetterStatus;

class StatusService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        return [];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        try {
            LetterStatus::create($request);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create status: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, LetterStatus $status): bool
    {
        try {
            $status->update($request);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update status: ' . $th->getMessage(), [
                'request' => $request,
                'status_id' => $status->id,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LetterStatus $status): bool
    {
        try {
            return $status->delete();
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete status: ' . $th->getMessage(), [
                'status_id' => $status->id,
            ]);
            return false;
        }
    }
}
