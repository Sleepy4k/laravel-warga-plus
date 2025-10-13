<?php

namespace App\Services\Web\Dashboard\Administration\Document;

use App\Contracts\Models;
use App\Enums\UploadFileType;
use App\Facades\File;
use App\Foundations\Service;
use App\Models\DocumentVersion;

class DocumentGalleryService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\DocumentInterface $documentInterface,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $documents = $this->documentInterface->all(
            ['id', 'title', 'description', 'is_archived'],
            ['versions:id,user_id,document_id,file_name,file_size,version_number,uploaded_at', 'versions.user:id', 'versions.user.personal:id,user_id,first_name,last_name'],
            [['is_archived', '=', false]]
        );

        return compact(
            'documents',
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentVersion $version): array
    {
        $filePath = File::getFilePath(UploadFileType::DOCUMENT, $version->file_path);
        if (!$filePath) {
            return [];
        }

        $defaultPath = config('filesystems.default') == 'local' ? 'private/' : 'public/';
        $filePath = storage_path('app/' . $defaultPath . $filePath);

        return [
            'file' => file_get_contents($filePath),
            'name' => $version->file_name,
            'type' => $version->file_type,
        ];
    }
}
