<?php

namespace App\Repositories\Models;

use App\Contracts\Models\DocumentCategoryInterface;
use App\Models\DocumentCategory;
use App\Repositories\EloquentRepository;

class DocumentCategoryRepository extends EloquentRepository implements DocumentCategoryInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(DocumentCategory $model)
    {
        $this->model = $model;
    }
}
