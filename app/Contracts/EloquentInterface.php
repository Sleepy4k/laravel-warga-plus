<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EloquentInterface
{
    /**
     * Wrap into transaction.
     *
     * @param  callable  $callback
     *
     * @return mixed
     */
    public function wrapIntoTransaction(callable $callback): mixed;

    /**
     * Get all models.
     *
     * @param  array  $columns
     * @param  array  $relations
     * @param  array  $wheres
     * @param  string  $orderBy
     * @param  bool  $latest
     * @param  array  $roles
     *
     * @return Collection|bool
     */
    public function all(array $columns = ['*'], array $relations = [], array $wheres = [], string $orderBy = 'created_at', bool $latest = true, array $roles = []): Collection|bool;

    /**
     * Get all models.
     *
     * @param  array  $columns
     * @param  bool  $first
     * @param  array  $relations
     * @param  array  $wheres
     * @param  string  $orderBy
     * @param  bool  $latest
     * @param  array  $roles
     *
     * @return Collection|Model|bool
     */
    public function get(array $columns = ['*'], bool $first = false, array $relations = [], array $wheres = [], string $orderBy = 'created_at', bool $latest = true, array $roles = []): Collection|Model|bool;

    /**
     * Get all in pagination models.
     *
     * @param  int  $paginate
     * @param  array  $columns
     * @param  array  $relations
     * @param  array  $wheres
     * @param  string  $orderBy
     * @param  bool  $latest
     * @param  array  $roles
     *
     * @return Collection|LengthAwarePaginator|bool
     */
    public function paginate(int $paginate = 10, array $columns = ['*'], array $relations = [], array $wheres = [], string $orderBy = 'created_at', bool $latest = true, array $roles = []): Collection|LengthAwarePaginator|bool;

    /**
     * Get all trashed models.
     *
     * @return Collection|bool
     */
    public function allTrashed(): Collection|bool;

    /**
     * Find model by id.
     *
     * @param  mixed  $modelId
     * @param  array  $columns
     * @param  array  $relations
     * @param  array  $appends
     *
     * @return Model|bool
     */
    public function findById(mixed $modelId, array $columns = ['*'], array $relations = [], array $appends = []): Model|bool;

    /**
     * Find model by custom id.
     *
     * @param  array  $wheres
     * @param  array  $columns
     * @param  array  $relations
     * @param  array  $appends
     *
     * @return Model|bool
     */
    public function findByCustomId(array $wheres = [], array $columns = ['*'], array $relations = []): Model|bool;

    /**
     * Find trashed model by id.
     *
     * @param  int  $modelId
     *
     * @return Model|bool
     */
    public function findTrashedById(int $modelId): Model|bool;

    /**
     * Find trashed model by custom id.
     *
     * @param  array  $wheres
     *
     * @return Model|bool
     */
    public function findTrashedByCustomId(array $wheres = []): Model|bool;

    /**
     * Find only trashed model by id.
     *
     * @param  int  $modelId
     *
     * @return Model|bool
     */
    public function findOnlyTrashedById(int $modelId): Model|bool;

    /**
     * Find only trashed model by custom id.
     *
     * @param  array  $wheres
     *
     * @return Model|bool
     */
    public function findOnlyTrashedByCustomId(array $wheres = []): Model|bool;

    /**
     * Create a model.
     *
     * @param  array  $payload
     *
     * @return Model|bool
     */
    public function create(array $payload): Model|bool;

    /**
     * Update existing model.
     *
     * @param  mixed  $modelId
     * @param  array  $payload
     *
     * @return bool
     */
    public function update(mixed $modelId, array $payload): bool;

    /**
     * Delete model by id.
     *
     * @param  mixed  $modelId
     *
     * @return bool
     */
    public function deleteById(mixed $modelId): bool;

    /**
     * Restore model by id.
     *
     * @param  int  $modelId
     *
     * @return bool
     */
    public function restoreById(int $modelId): bool;

    /**
     * Permanently delete model by id.
     *
     * @param  int  $modelId
     *
     * @return bool
     */
    public function permanentlyDeleteById(int $modelId): bool;

    /**
     * Get all models count.
     *
     * @return int
     */
    public function count(): int;

    /**
     * Get all searchable fields.
     *
     * @return int
     */
    public function getSearchableFields(): array;
}
