<?php

namespace App\Observers;

use App\Enums\UploadFileType;
use App\Facades\File;
use App\Models\UserPersonalData;

class UserPersonalDataObserver
{
    /**
     * Handle the Product "updating" event.
     */
    public function updating(UserPersonalData $userPersonalData): void
    {
        if ($userPersonalData->isDirty('avatar') && ($userPersonalData->avatar != null || $userPersonalData->avatar != '')) {
            if (strpos($userPersonalData->avatar, 'silhouette') !== false) {
                $userPersonalData->avatar = $userPersonalData->avatar
                    ? File::saveSingleFile(UploadFileType::AVATAR, $userPersonalData->avatar)
                    : null;
            } else {
                $oldImage = $userPersonalData->getOriginal('avatar', null);
                $userPersonalData->avatar = $userPersonalData->avatar
                    ? File::updateSingleFile(UploadFileType::AVATAR, $userPersonalData->avatar, $oldImage)
                    : File::deleteFile(UploadFileType::AVATAR, $oldImage);
            }

        }
    }
}
