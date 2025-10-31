<?php

namespace App\Repositories\Models;

use App\Contracts\Models\ReportCategoryInterface;
use App\Models\ReportCategory;
use App\Repositories\EloquentRepository;

class ReportCategoryRepository extends EloquentRepository implements ReportCategoryInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(ReportCategory $model)
    {
        $this->model = $model;
    }
}
