<?php

namespace App\Services\Web\Dashboard\Setting;

use App\Enums\ApplicationSettingType;
use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ApplicationService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $maintenanceFile = storage_path('framework/down');
        $isMaintenanceMode = file_exists($maintenanceFile);
        $settings = Setting::allAsKeyValue();
        $timezones = timezone_identifiers_list();
        $types = ApplicationSettingType::toArray();
        $types = array_combine($types, $types);

        return compact('isMaintenanceMode', 'settings', 'timezones', 'types');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        try {
            if (file_exists(storage_path('framework/down'))) {
                Artisan::call('up');
            } else {
                Artisan::call('down', [
                    '--secret' => $request['secret'],
                ]);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, string $appSettingType): bool
    {
        try {
            DB::beginTransaction();

            if ($appSettingType === 'footer') {
                $links = $request['link'] ?? [];
                unset($request['link']);

                foreach ($links as $link) {
                    $key = $link['key'] ?? 'dynamic_menu_' . rand(1000, 9999);

                    $request["{$appSettingType}_{$key}_url"] = $link['url'];
                    $request["{$appSettingType}_{$key}_title"] = $link['title'];
                }
            }

            foreach ($request as $key => $value) {
                $settingKey = str_replace("{$appSettingType}_", '', $key);

                Setting::updateOrCreate(
                    ['group' => $appSettingType, 'key' => $settingKey],
                    ['value' => $value]
                );
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update setting: ' . $e->getMessage(), [
                'setting_type' => $appSettingType,
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $settingKey
     *
     * @return bool
     */
    public function destroy(string $settingKey): bool
    {
        try {
            Setting::whereIn('key', ["{$settingKey}_url", "{$settingKey}_title"])->delete();

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete setting: ' . $th->getMessage(), [
                'setting_id' => $settingKey
            ]);
            return false;
        }
    }
}
