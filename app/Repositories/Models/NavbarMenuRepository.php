<?php

namespace App\Repositories\Models;

use App\Contracts\Models\NavbarMenuInterface;
use App\Models\NavbarMenu;
use App\Repositories\EloquentRepository;

class NavbarMenuRepository extends EloquentRepository implements NavbarMenuInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(NavbarMenu $model)
    {
        $this->model = $model;
    }
}
