<?php

namespace App\Repositories\Models;

use App\Contracts\Models\ReportInterface;
use App\Models\Report;
use App\Repositories\EloquentRepository;

class ReportRepository extends EloquentRepository implements ReportInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(Report $model)
    {
        $this->model = $model;
    }
}
