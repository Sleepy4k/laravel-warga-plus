<?php

namespace App\Repositories\Models;

use App\Contracts\Models\PermissionInterface;
use App\Models\Permission;
use App\Repositories\EloquentRepository;

class PermissionRepository extends EloquentRepository implements PermissionInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(Permission $model)
    {
        $this->model = $model;
    }
}
