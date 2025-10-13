<?php

namespace App\Repositories\Models;

use App\Contracts\Models\ArticleInterface;
use App\Models\Article;
use App\Repositories\EloquentRepository;

class ArticleRepository extends EloquentRepository implements ArticleInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(Article $model)
    {
        $this->model = $model;
    }
}
