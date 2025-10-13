<?php

namespace App\Services\Web\Dashboard\Article;

use App\Contracts\Models;
use App\Enums\ReportLogType;
use App\Foundations\Service;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

class ArticleService extends Service
{
    /**
     * Model contract constructor.
     */
    public function __construct(
        private Models\ArticleInterface $articleInterface,
        private Models\ArticleCategoryInterface $articleCategoryInterface,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        $categories = $this->articleCategoryInterface->all(['id', 'label']);
        $totalCategory = $categories->count();

        if (isUserHasRole(config('rbac.role.highest'))) {
            $totalArticle = $this->articleInterface->count();
        } else {
            $totalArticle = $this->articleInterface->all(['id'], [], [['author_id', '=', auth('web')->id()]])->count();
        }

        return compact('categories', 'totalCategory', 'totalArticle');
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
        $request['slug'] = str($request['title'])->slug();
        $request['author_id'] = auth('web')->id();
        $request['content'] = htmlspecialchars($request['content'], ENT_QUOTES, 'UTF-8');

        DB::beginTransaction();

        try {
            $article = $this->articleInterface->create($request);
            if (!is_bool($article)) {
                $article->categories()->sync($request['categories']);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }

        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     *
     * @return array
     */
    public function show(Article $article): array
    {
        $article->load(['categories:id,label']);

        return [
            'article' => $article,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $request
     * @param int $id
     *
     * @return bool
     */
    public function update(array $request, Article $article): bool
    {
        try {
            $request['slug'] = str($request['title'])->slug();
            $request['author_id'] = auth('web')->id();

            $article->update($request);
            $article->categories()->sync($request['categories']);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to update article: ' . $th->getMessage(), [
                'request' => $request,
                'article_id' => $article->id,
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
    public function destroy(Article $article): bool
    {
        try {
            $article->load('categories');
            $article->categories()->detach();
            $article->delete();

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, 'Failed to delete article: ' . $th->getMessage(), [
                'article_id' => $article->id,
            ]);
            return false;
        }
    }

    /**
     * Get article statistics.
     *
     * @return array
     */
    public function getArticleStats(): array
    {
        if (isUserHasRole(config('rbac.role.highest'))) {
            $totalArticle = $this->articleInterface->count();
            $totalCategory = $this->articleCategoryInterface->count();
            return compact('totalArticle', 'totalCategory');
        }

        $totalArticle = $this->articleInterface->all(['id'], [], [['author_id', '=', auth('web')->id()]])->count();
        $totalCategory = $this->articleCategoryInterface->count();
        return compact('totalArticle', 'totalCategory');
    }
}
