<?php

namespace App\Services\Web\Dashboard\Administration\Document;

use App\Contracts\Models;
use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\DocumentCategory;

class DocumentCategoryService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\DocumentCategoryInterface $documentCategoryInterface
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        return [];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $request): bool
    {
        try {
            $category = $this->documentCategoryInterface->create($request);

            return $category ? true : false;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create document category: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request, DocumentCategory $category): bool
    {
        try {
            $category->update($request);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update document category: ' . $th->getMessage(), [
                'request' => $request,
                'category_id' => $category->id,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentCategory $category): bool
    {
        try {
            if ($category->documents()->count() > 0) {
                $category->documents()->each(function ($document) {
                    $document->delete();
                });
            }

            $category->delete();

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete document category: ' . $th->getMessage(), [
                'category_id' => $category->id,
            ]);
            return false;
        }
    }
}
