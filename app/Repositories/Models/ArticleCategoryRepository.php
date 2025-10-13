<?php

namespace App\Repositories\Models;

use App\Contracts\Models\ArticleCategoryInterface;
use App\Models\ArticleCategory;
use App\Repositories\EloquentRepository;

class ArticleCategoryRepository extends EloquentRepository implements ArticleCategoryInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(ArticleCategory $model)
    {
        $this->model = $model;
    }
}
