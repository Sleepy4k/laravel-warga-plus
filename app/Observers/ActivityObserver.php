<?php

namespace App\Observers;

use App\Enums\UploadFileType;
use App\Facades\File;
use App\Models\Activity;

class ActivityObserver
{
    /**
     * Handle the Activity "creating" event.
     */
    public function creating(Activity $activity): void
    {
        $activity->image = $activity->image
            ? File::saveSingleFile(UploadFileType::ACTIVITY, $activity->image)
            : null;
    }

    /**
     * Handle the Activity "updating" event.
     */
    public function updating(Activity $activity): void
    {
        if ($activity->isDirty('image') && ($activity->image != null || $activity->image != '')) {
            $oldImage = $activity->getOriginal('image', null);

            if ($oldImage == null) {
                $activity->image = $activity->image
                    ? File::saveSingleFile(UploadFileType::ACTIVITY, $activity->image)
                    : null;
            } else {
                $activity->image = $activity->image
                    ? File::updateSingleFile(UploadFileType::ACTIVITY, $activity->image, $oldImage)
                    : File::deleteFile(UploadFileType::ACTIVITY, $oldImage);
            }
        }
    }

    /**
     * Handle the Activity "deleting" event.
     */
    public function deleting(Activity $activity): void
    {
        $activity->image
            ? File::deleteFile(UploadFileType::ACTIVITY, $activity->image)
            : null;
    }
}
