<?php

namespace App\Services\Web\Dashboard\Administration\Document;

use App\Contracts\Models;
use App\Enums\ReportLogType;
use App\Enums\UploadFileType;
use App\Facades\File;
use App\Foundations\Service;
use App\Models\Document;
use App\Models\DocumentVersion;

class DocumentVersionService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\DocumentVersionInterface $documentVersionInterface,
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Document $document, array $request): bool
    {
        try {
            $file = $request['file'];
            $version = $this->documentVersionInterface->create([
                'document_id' => $document->id,
                'user_id' => auth('web')->id(),
                'version_number' => ($document->versions()->max('version_number') ?? 0) + 1,
                'uploaded_at' => now(),
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $file,
                'file_size' => $file->getSize(),
                'file_type' => $file->getMimeType(),
            ]);

            if (!$version) {
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create document file: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentVersion $version): bool
    {
        try {
            $this->documentVersionInterface->deleteById($version->id);
            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete document file: ' . $th->getMessage(), [
                'version_id' => $version->id,
            ]);
            return false;
        }
    }
}
