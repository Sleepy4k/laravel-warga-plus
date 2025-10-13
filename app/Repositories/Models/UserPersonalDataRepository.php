<?php

namespace App\Repositories\Models;

use App\Contracts\Models\UserPersonalDataInterface;
use App\Models\UserPersonalData;
use App\Repositories\EloquentRepository;

class UserPersonalDataRepository extends EloquentRepository implements UserPersonalDataInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(UserPersonalData $model)
    {
        $this->model = $model;
    }
}
