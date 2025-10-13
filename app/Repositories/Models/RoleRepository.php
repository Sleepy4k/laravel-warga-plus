<?php

namespace App\Repositories\Models;

use App\Contracts\Models\RoleInterface;
use App\Models\Role;
use App\Repositories\EloquentRepository;
use App\Traits\SystemLog;
use Illuminate\Database\Eloquent\Model;

class RoleRepository extends EloquentRepository implements RoleInterface
{
    use SystemLog;

    /**
     * Base respository constructor
     *
     * @param  Model  $model
     */
    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    /**
     * Create a model.
     *
     * @param  array  $payload
     * @return Model|bool
     */
    public function create(array $payload): Model|bool
    {
        $transaction = $this->wrapIntoTransaction(function () use ($payload) {
            if (array_key_exists('permissions', $payload)) {
                $permission = $payload['permissions'];
                unset($payload['permissions']);
            }

            $model = $this->model->query()->create($payload);

            if (!empty($permission)) $model->syncPermissions($permission);

            return $model->fresh();
        });

        return $transaction;
    }

    /**
     * Update existing model.
     *
     * @param  mixed  $modelId
     * @param  array  $payload
     * @return bool
     */
    public function update(mixed $modelId, array $payload): bool
    {
        $transaction = $this->wrapIntoTransaction(function () use ($modelId, $payload) {
            if (array_key_exists('permissions', $payload)) {
                $permission = $payload['permissions'];
                unset($payload['permissions']);
            }

            $model = $this->findById($modelId);

            if (!empty($permission)) $model->syncPermissions($permission);

            return $model->update($payload);
        });

        return $transaction;
    }

    /**
     * Delete model by id.
     *
     * @param  mixed  $modelId
     * @return Model
     */
    public function deleteById(mixed $modelId): bool
    {
        $transaction = $this->wrapIntoTransaction(function () use ($modelId) {
            $roleId = $this->model->query()->findOrFail($modelId);
            $roleId->users()->update(['role_id' => config('rbac.role.default')]);

            return $roleId->delete();
        });

        return $transaction;
    }
}
