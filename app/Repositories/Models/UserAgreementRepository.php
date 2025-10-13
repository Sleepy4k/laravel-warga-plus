<?php

namespace App\Repositories\Models;

use App\Contracts\Models\UserAgreementInterface;
use App\Models\UserAgreement;
use App\Repositories\EloquentRepository;

class UserAgreementRepository extends EloquentRepository implements UserAgreementInterface
{
    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(UserAgreement $model)
    {
        $this->model = $model;
    }
}
