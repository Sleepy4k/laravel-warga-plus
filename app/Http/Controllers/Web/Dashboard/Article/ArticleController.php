<?php

namespace App\Http\Controllers\Web\Dashboard\Article;

use App\DataTables\Article\ArticleDataTable;
use App\Foundations\Controller;
use App\Http\Requests\Web\Dashboard\Article\StoreRequest;
use App\Http\Requests\Web\Dashboard\Article\UpdateRequest;
use App\Models\Article;
use App\Services\Web\Dashboard\Article\ArticleService;
use App\Traits\Authorizable;

class ArticleController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ArticleService $service,
        private $policy = Article::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
            'show' => 'view',
            'update' => 'update',
            'destroy' => 'delete',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(ArticleDataTable $dataTable)
    {
        return $dataTable->render('pages.dashboard.article.index', $this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$this->service->store($request->validated())) {
            return $this->sendResponse(null, 'Failed to create article. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getArticleStats(), 'Article successfully created.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('pages.dashboard.article.show', $this->service->show($article));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Article $article)
    {
        if (!$this->service->update($request->validated(), $article)) {
            return $this->sendResponse(null, 'Failed to update article. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getArticleStats(), 'Article successfully updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        if (!$this->service->destroy($article)) {
            return $this->sendResponse(null, 'Failed to delete article. Please try again.', 500);
        }

        return $this->sendResponse($this->service->getArticleStats(), 'Article successfully deleted.', 200);
    }
}
