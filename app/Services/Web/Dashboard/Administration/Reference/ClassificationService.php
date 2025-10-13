<?php

namespace App\Services\Web\Dashboard\Administration\Reference;

use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\LetterClassification;

class ClassificationService extends Service
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
            LetterClassification::create($request);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create classification: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, LetterClassification $classification): bool
    {
        try {
            $classification->update($request);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update classification: ' . $th->getMessage(), [
                'request' => $request,
                'classification_id' => $classification->id,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LetterClassification $classification): bool
    {
        try {
            return $classification->delete();
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete classification: ' . $th->getMessage(), [
                'classification_id' => $classification->id,
            ]);
            return false;
        }
    }
}
