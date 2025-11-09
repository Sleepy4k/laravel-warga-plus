<?php

namespace App\Services\Web\Dashboard\Information;

use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\InformationCategory;

class InformationCategoryService extends Service
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
            $request['name'] = ucwords($request['name']);
            $category = InformationCategory::create($request);

            return $category ? true : false;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create information category: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, InformationCategory $category): bool
    {
        try {
            $request['name'] = ucwords($request['name']);
            $category->update($request);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update information category: ' . $th->getMessage(), [
                'request' => $request,
                'category_id' => $category->id,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InformationCategory $category): bool
    {
        try {
            if ($category->informations()->count() > 0) {
                $category->informations()->each(function ($information) {
                    $information->delete();
                });
            }

            $category->delete();

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete information category: ' . $th->getMessage(), [
                'category_id' => $category->id,
            ]);
            return false;
        }
    }
}
