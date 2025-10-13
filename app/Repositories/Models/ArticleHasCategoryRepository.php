<?php

namespace App\Repositories\Models;

use App\Contracts\Models\ArticleHasCategoryInterface;
use App\Models\ArticleHasCategory;
use App\Repositories\EloquentRepository;

class ArticleHasCategoryRepository extends EloquentRepository implements ArticleHasCategoryInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(ArticleHasCategory $model)
    {
        $this->model = $model;
    }
}
