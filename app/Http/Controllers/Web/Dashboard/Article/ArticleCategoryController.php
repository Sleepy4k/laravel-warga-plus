<?php

namespace App\Http\Controllers\Web\Dashboard\Article;

use App\DataTables\Article\ArticleCategoryDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Article\StoreCategoryRequest;
use App\Http\Requests\Web\Dashboard\Article\UpdateCategoryRequest;
use App\Models\ArticleCategory;
use App\Services\Web\Dashboard\Article\ArticleCategoryService;
use App\Traits\Authorizable;

class ArticleCategoryController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ArticleCategoryService $service,
        private $policy = ArticleCategory::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
            'update' => 'update',
            'destroy' => 'delete',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(ArticleCategoryDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.article.category.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create article category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Article category successfully created.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, ArticleCategory $articleCategory)
    {
        if (!$this->service->update($request->validated(), $articleCategory)) {
            return $this->sendResponse(null, 'Failed to update article category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Article category successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArticleCategory $articleCategory)
    {
        if (!$this->service->destroy($articleCategory)) {
            return $this->sendResponse(null, 'Failed to delete article category. Please try again.', 500);
        }

        return $this->sendResponse(null, 'Article category successfully deleted.', 200);
    }
}
