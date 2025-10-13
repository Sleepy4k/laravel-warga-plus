<?php

namespace App\Repositories\Models;

use App\Contracts\Models\ProductCategoryInterface;
use App\Models\ProductCategory;
use App\Repositories\EloquentRepository;

class ProductCategoryRepository extends EloquentRepository implements ProductCategoryInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(ProductCategory $model)
    {
        $this->model = $model;
    }
}
