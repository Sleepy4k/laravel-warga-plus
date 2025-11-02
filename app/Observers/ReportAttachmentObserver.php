<?php

namespace App\Observers;

use App\Enums\UploadFileType;
use App\Facades\File;
use App\Models\ReportAttachment;

class ReportAttachmentObserver
{
    /**
     * Handle the ReportAttachment "creating" event.
     */
    public function creating(ReportAttachment $attachment): void
    {
        $attachment->path = $attachment->path
            ? File::saveSingleFile(UploadFileType::REPORT_ATTACHMENT, $attachment->path)
            : null;
    }

    /**
     * Handle the ReportAttachment "deleting" event.
     */
    public function deleting(ReportAttachment $attachment): void
    {
        $attachment->path
            ? File::deleteFile(UploadFileType::REPORT_ATTACHMENT, $attachment->path)
            : null;
    }
}
