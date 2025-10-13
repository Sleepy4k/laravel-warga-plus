<?php

namespace App\Repositories\Models;

use App\Contracts\Models\ActivityCategoryInterface;
use App\Models\ActivityCategory;
use App\Repositories\EloquentRepository;

class ActivityCategoryRepository extends EloquentRepository implements ActivityCategoryInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(ActivityCategory $model)
    {
        $this->model = $model;
    }
}
