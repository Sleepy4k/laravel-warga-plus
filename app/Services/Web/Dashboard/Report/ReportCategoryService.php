<?php

namespace App\Services\Web\Dashboard\Report;

use App\Contracts\Models;
use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\ReportCategory;

class ReportCategoryService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\ReportCategoryInterface $reportCategoryInterface
    ) {}

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
            $request['name'] = ucwords($request['name']);
            $category = $this->reportCategoryInterface->create($request);

            return $category ? true : false;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create report category: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, ReportCategory $category): bool
    {
        try {
            $request['name'] = ucwords($request['name']);
            $category->update($request);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update report category: ' . $th->getMessage(), [
                'request' => $request,
                'category_id' => $category->id,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportCategory $category): bool
    {
        try {
            if ($category->reports()->count() > 0) {
                $category->reports()->each(function ($report) {
                    $report->delete();
                });
            }

            $category->delete();

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete report category: ' . $th->getMessage(), [
                'category_id' => $category->id,
            ]);
            return false;
        }
    }
}
