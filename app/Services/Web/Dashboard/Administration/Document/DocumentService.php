<?php

namespace App\Services\Web\Dashboard\Administration\Document;

use App\Contracts\Models;
use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\Document;

class DocumentService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\DocumentInterface $documentInterface,
        private Models\DocumentVersionInterface $documentVersionInterface,
        private Models\DocumentCategoryInterface $documentCategoryInterface
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        $documents = $this->documentInterface->all(['id', 'is_archived']);
        $categories = $this->documentCategoryInterface->all(['id', 'name']);

        $totalDocument = $documents->count();
        $totalDocumentArchived = $documents->filter(fn ($doc) => $doc->is_archived)->count();
        $totalDocumentCategories = $categories->count();

        return compact(
            'categories',
            'totalDocument',
            'totalDocumentArchived',
            'totalDocumentCategories'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        try {
            $document = $this->documentInterface->create([
                'title' => $request['title'],
                'description' => $request['description'],
                'category_id' => $request['category_id'],
                'is_archived' => $request['is_archived'] ?? false,
                'last_modified_at' => now(),
            ]);

            if (!$document) return false;

            $file = $request['file'];

            $version = $this->documentVersionInterface->create([
                'document_id' => $document->id,
                'user_id' => auth('web')->id(),
                'version_number' => 1,
                'uploaded_at' => now(),
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $file,
                'file_size' => $file->getSize(),
                'file_type' => $file->getMimeType(),
            ]);

            if (!$version) {
                $this->documentInterface->deleteById($document->id);
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create document: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document): array
    {
        $document->load('category:id,name');
        $categories = $this->documentCategoryInterface->all(['id', 'name']);

        return compact('document', 'categories');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, Document $document): bool
    {
        try {
            $doc = $this->documentInterface->update($document->id, [
                'title' => $request['title'],
                'description' => $request['description'],
                'category_id' => $request['category_id'],
                'is_archived' => $request['is_archived'] ?? false,
                'last_modified_at' => now(),
            ]);

            if (!$doc) return false;

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update document: ' . $th->getMessage(), [
                'request' => $request,
                'document_id' => $document->id,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document): bool
    {
        try {
            if ($document->versions()->count() > 0) {
                foreach ($document->versions as $version) {
                    $version->delete();
                }
            }

            return $document->delete();
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete document: ' . $th->getMessage(), [
                'document_id' => $document->id,
            ]);
            return false;
        }
    }

    /**
     * Get document statistics.
     *
     * @return array
     */
    public function getDocumentStats(): array
    {
        $documents = $this->documentInterface->all(['id', 'is_archived']);

        $totalDocument = $documents->count();
        $totalDocumentArchived = $documents->filter(fn ($doc) => $doc->is_archived)->count();
        $totalDocumentCategories = $this->documentCategoryInterface->count();

        return compact(
            'totalDocument',
            'totalDocumentArchived',
            'totalDocumentCategories'
        );
    }
}
