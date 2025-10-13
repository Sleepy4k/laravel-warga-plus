<?php

namespace App\Repositories\Models;

use App\Contracts\Models\DocumentVersionInterface;
use App\Models\DocumentVersion;
use App\Repositories\EloquentRepository;

class DocumentVersionRepository extends EloquentRepository implements DocumentVersionInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(DocumentVersion $model)
    {
        $this->model = $model;
    }
}
