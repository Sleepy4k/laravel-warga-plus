<?php

namespace App\Repositories\Models;

use App\Contracts\Models\NavbarMenuMetaInterface;
use App\Models\NavbarMenuMeta;
use App\Repositories\EloquentRepository;

class NavbarMenuMetaRepository extends EloquentRepository implements NavbarMenuMetaInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(NavbarMenuMeta $model)
    {
        $this->model = $model;
    }
}
