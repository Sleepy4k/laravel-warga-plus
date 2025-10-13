<?php

namespace App\Services\Web\Dashboard\Activity;

use App\Contracts\Models;
use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\ActivityCategory;

class ActivityCategoryService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\ActivityCategoryInterface $activityCategoryInterface
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        return [];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     *
     * @return bool
     */
    public function store(array $request): bool
    {
        try {
            $category = $this->activityCategoryInterface->create([
                'name' => $request['name'],
                'label' => $request['label'],
            ]);

            return $category ? true : false;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create activity category: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $request
     * @param int $id
     *
     * @return bool
     */
    public function update(array $request, ActivityCategory $activityCategory): bool
    {
        try {
            $activityCategory->update([
                'name' => $request['name'],
                'label' => $request['label'],
            ]);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update activity category: ' . $th->getMessage(), [
                'activity_category_id' => $activityCategory->id,
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroy(ActivityCategory $activityCategory): bool
    {
        try {
            if ($activityCategory->activities()->count() > 0) {
                $activityCategory->activities()->each(function ($activity) {
                    $activity->delete();
                });
            }

            $activityCategory->delete();

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete activity category: ' . $th->getMessage(), [
                'activity_category_id' => $activityCategory->id,
            ]);
            return false;
        }
    }
}
