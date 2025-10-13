<?php

namespace App\Repositories\Models;

use App\Contracts\Models\MenuMetaInterface;
use App\Models\MenuMeta;
use App\Repositories\EloquentRepository;

class MenuMetaRepository extends EloquentRepository implements MenuMetaInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(MenuMeta $model)
    {
        $this->model = $model;
    }
}
