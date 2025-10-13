<?php

namespace App\Repositories\Models;

use App\Contracts\Models\MenuInterface;
use App\Models\Menu;
use App\Repositories\EloquentRepository;

class MenuRepository extends EloquentRepository implements MenuInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(Menu $model)
    {
        $this->model = $model;
    }
}
