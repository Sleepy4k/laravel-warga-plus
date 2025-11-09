<?php

namespace App\Repositories\Models;

use App\Contracts\Models\InformationInterface;
use App\Models\Information;
use App\Repositories\EloquentRepository;

class InformationRepository extends EloquentRepository implements InformationInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(Information $model)
    {
        $this->model = $model;
    }
}
