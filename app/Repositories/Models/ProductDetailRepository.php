<?php

namespace App\Repositories\Models;

use App\Contracts\Models\ProductDetailInterface;
use App\Models\ProductDetail;
use App\Repositories\EloquentRepository;

class ProductDetailRepository extends EloquentRepository implements ProductDetailInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(ProductDetail $model)
    {
        $this->model = $model;
    }
}
