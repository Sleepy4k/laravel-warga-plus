<?php

namespace App\Observers;

use App\Enums\UploadFileType;
use App\Facades\File;
use App\Models\Setting;

class SettingObserver
{
    /**
     * Handle the Setting "creating" event.
     */
    public function creating(Setting $setting): void
    {
        foreach ($setting->getAttributes() as $key => $value) {
            if ($value instanceof \Illuminate\Http\UploadedFile) {
                $setting->$key = File::saveSingleFile(UploadFileType::SETTING, $value);
            }
        }
    }

    /**
     * Handle the Setting "updating" event.
     */
    public function updating(Setting $setting): void
    {
        foreach ($setting->getAttributes() as $key => $value) {
            if ($value instanceof \Illuminate\Http\UploadedFile) {
                $oldFile = $setting->getOriginal($key, null);
                $setting->$key = File::saveSingleFile(UploadFileType::SETTING, $value);
                if ($oldFile) {
                    File::deleteFile(UploadFileType::SETTING, $oldFile);
                }
            }
        }
    }
}
