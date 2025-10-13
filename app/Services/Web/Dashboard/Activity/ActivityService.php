<?php

namespace App\Services\Web\Dashboard\Activity;

use App\Contracts\Models;
use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\Activity;

class ActivityService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\ActivityInterface $activityInterface,
        private Models\ActivityCategoryInterface $activityCategoryInterface
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $categories = $this->activityCategoryInterface->all(['id', 'label']);
        $totalCategory = $categories->count();
        $totalActivity = $this->activityInterface->count();

        return compact('categories', 'totalCategory', 'totalActivity');
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
            $activity = $this->activityInterface->create($request);

            return $activity ? true : false;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create activity: ' . $th->getMessage(), [
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
    public function update(array $request, Activity $activity): bool
    {
        try {
            if (!isset($request['image']) || empty($request['image'])) {
                unset($request['image']);
            }

            $activity->update($request);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update activity: ' . $th->getMessage(), [
                'activity_id' => $activity->id,
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
    public function destroy(Activity $activity): bool
    {
        try {
            $activity->delete();

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete activity: ' . $th->getMessage(), [
                'activity_id' => $activity->id,
            ]);
            return false;
        }
    }

    /**
     * Get activity statistics.
     *
     * @return array
     */
    public function getActivityStats(): array
    {
        $totalActivity = $this->activityInterface->count();
        $totalCategory = $this->activityCategoryInterface->count();

        return compact('totalActivity', 'totalCategory');
    }
}
