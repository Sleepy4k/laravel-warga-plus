<?php

namespace App\Repositories\Models;

use App\Contracts\Models\DocumentInterface;
use App\Models\Document;
use App\Repositories\EloquentRepository;

class DocumentRepository extends EloquentRepository implements DocumentInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(Document $model)
    {
        $this->model = $model;
    }
}
