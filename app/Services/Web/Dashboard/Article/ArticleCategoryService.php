<?php

namespace App\Services\Web\Dashboard\Article;

use App\Contracts\Models;
use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\ArticleCategory;

class ArticleCategoryService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\ArticleCategoryInterface $articleCategoryInterface,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        return [];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     *
     * @return bool
     */
    public function store(array $request): bool
    {
        try {
            $category = $this->articleCategoryInterface->create([
                'name' => $request['name'],
                'label' => $request['label'],
            ]);

            return $category ? true : false;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to create article category: ' . $th->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $request
     * @param int $id
     *
     * @return bool
     */
    public function update(array $request, ArticleCategory $articleCategory): bool
    {
        try {
            $articleCategory->update([
                'name' => $request['name'],
                'label' => $request['label'],
            ]);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update article category: ' . $th->getMessage(), [
                'article_category_id' => $articleCategory->id,
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroy(ArticleCategory $articleCategory): bool
    {
        try {
            if ($articleCategory->articles()->count() > 0) {
                $articleCategory->articles()->detach();
            }

            $articleCategory->delete();

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete article category: ' . $th->getMessage(), [
                'article_category_id' => $articleCategory->id,
            ]);
            return false;
        }
    }
}
