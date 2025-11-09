<?php

namespace App\Services\Web\Dashboard\Information;

use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\Information;
use App\Models\InformationCategory;
use Illuminate\Support\Facades\DB;

class InformationService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $categories = InformationCategory::select('id', 'name', 'created_at')->orderBy('created_at', 'asc')->get();

        return array_merge(
            [
                'categories' => $categories
            ],
            $this->getInformationStats()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        try {
            $userId = auth('web')->id();

            DB::beginTransaction();

            if ($request['category_id'] === 'other' && isset($request['new-category'])) {
                $category = InformationCategory::firstOrCreate([
                    'name' => ucwords(strtolower($request['new-category'])),
                ]);
                $request['category_id'] = $category->id;
                unset($request['new-category']);
            }

            Information::create(array_merge($request, ['user_id' => $userId]));

            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create information: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, Information $information): bool
    {
        try {
            DB::beginTransaction();

            if ($request['category_id'] === 'other' && isset($request['new-category'])) {
                $category = InformationCategory::firstOrCreate([
                    'name' => ucwords(strtolower($request['new-category'])),
                ]);
                $request['category_id'] = $category->id;
                unset($request['new-category']);
            }

            $information->update($request);

            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update information: ' . $th->getMessage(), [
                'request' => $request,
                'information_id' => $information->id,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Information $information): bool
    {
        try {
            $information->delete();

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete information: ' . $th->getMessage(), [
                'information_id' => $information->id,
            ]);
            return false;
        }
    }

    /**
     * Get information statistics.
     *
     * @return array
     */
    public function getInformationStats(): array
    {
        $informations = Information::select('created_at')->get();

        $currentDate = now();
        $totalInformations = $informations->count();
        $monthlyInformations = $informations->whereBetween('created_at', [$currentDate->startOfMonth(), $currentDate->endOfMonth()])->count();
        $dailyInformations = $informations->whereBetween('created_at', [$currentDate->startOfDay(), $currentDate->endOfDay()])->count();

        return [
            'total_informations' => $totalInformations,
            'monthly_informations' => $monthlyInformations,
            'daily_informations' => $dailyInformations,
        ];
    }
}
