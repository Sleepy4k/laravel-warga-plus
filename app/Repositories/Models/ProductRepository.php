<?php

namespace App\Repositories\Models;

use App\Contracts\Models\ProductInterface;
use App\Models\Product;
use App\Repositories\EloquentRepository;

class ProductRepository extends EloquentRepository implements ProductInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(Product $model)
    {
        $this->model = $model;
    }
}
