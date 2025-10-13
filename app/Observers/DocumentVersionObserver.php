<?php

namespace App\Observers;

use App\Enums\UploadFileType;
use App\Facades\File;
use App\Models\DocumentVersion;

class DocumentVersionObserver
{
    /**
     * Handle the DocumentVersion "creating" event.
     */
    public function creating(DocumentVersion $documentVersion): void
    {
        $documentVersion->file_path = $documentVersion->file_path
            ? File::saveSingleFile(UploadFileType::DOCUMENT, $documentVersion->file_path)
            : null;
    }

    /**
     * Handle the DocumentVersion "deleting" event.
     */
    public function deleting(DocumentVersion $documentVersion): void
    {
        $documentVersion->file_path
            ? File::deleteFile(UploadFileType::DOCUMENT, $documentVersion->file_path)
            : null;
    }
}
