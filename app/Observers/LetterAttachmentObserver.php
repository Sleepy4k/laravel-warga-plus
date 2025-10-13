<?php

namespace App\Observers;

use App\Enums\UploadFileType;
use App\Facades\File;
use App\Models\LetterAttachment;

class LetterAttachmentObserver
{
    /**
     * Handle the LetterAttachment "creating" event.
     */
    public function creating(LetterAttachment $attachment): void
    {
        $attachment->path = $attachment->path
            ? File::saveSingleFile(UploadFileType::LETTER_ATTACHMENT, $attachment->path)
            : null;
    }

    /**
     * Handle the LetterAttachment "deleting" event.
     */
    public function deleting(LetterAttachment $attachment): void
    {
        $attachment->path
            ? File::deleteFile(UploadFileType::LETTER_ATTACHMENT, $attachment->path)
            : null;
    }
}
