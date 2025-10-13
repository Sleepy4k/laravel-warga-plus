<?php

namespace App\Repositories\Models;

use App\Contracts\Models\ActivityInterface;
use App\Models\Activity;
use App\Repositories\EloquentRepository;

class ActivityRepository extends EloquentRepository implements ActivityInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(Activity $model)
    {
        $this->model = $model;
    }
}
