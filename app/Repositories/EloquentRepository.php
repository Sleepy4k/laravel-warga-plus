<?php

namespace App\Repositories;

use App\Contracts\EloquentInterface;
use App\Enums\ReportLogType;
use App\Traits\SystemLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentRepository implements EloquentInterface
{
    use SystemLog;

    /**
     * @var Model
     */
    protected $model;

    /**
     * Base respository constructor.
     *
     * @param  Model  $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Wrap into transaction.
     *
     * @param  callable  $callback
     *
     * @return mixed
     */
    public function wrapIntoTransaction(callable $callback): mixed
    {
        try {
            return $this->model->getConnection()->transaction($callback);
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            return false;
        }
    }

    /**
     * Get all models.
     *
     * @param  array  $columns
     * @param  array  $relations
     * @param  array  $wheres
     * @param  string  $orderBy
     * @param  bool  $latest
     * @param  array  $roles
     * @return Collection|bool
     */
    public function all(array $columns = ['*'], array $relations = [], array $wheres = [], string $orderBy = 'created_at', bool $latest = true, array $roles = []): Collection|bool
    {
        try {
            $model = $this->model->with($relations);

            if (!empty($orderBy)) $model->orderBy($orderBy, $latest ? 'desc' : 'asc');

            if (!empty($wheres)) {
                $isOrCase = false;

                foreach ($wheres as $key => $value) {
                    if ($value[0] !== 'whereMode') continue;

                    $isOrCase = $value[1] === 'or';
                    unset($wheres[$key]);
                }

                if (!$isOrCase) $model->where($wheres);
                else {
                    $model->where(function ($query) use ($wheres) {
                        foreach ($wheres as $key => $where) {
                            if (count($where) === 2) {
                                $query->orWhere($where[0], $where[1]);
                            } else {
                                $query->orWhere($where[0], $where[1], $where[2]);
                            }
                        }
                    });
                }
            }

            if (!empty($roles)) $model->role($roles);

            return $model->get($columns);
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            return false;
        }
    }

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
     * @return Collection|Model|bool
     */
    public function get(array $columns = ['*'], bool $first = false, array $relations = [], array $wheres = [], string $orderBy = 'created_at', bool $latest = true, array $roles = []): Collection|Model|bool
    {
        try {
            $model = $this->model->with($relations);

            if (!empty($orderBy)) $model->orderBy($orderBy, $latest ? 'desc' : 'asc');

            if (!empty($wheres)) {
                $isOrCase = false;

                foreach ($wheres as $key => $value) {
                    if ($value[0] !== 'whereMode') continue;

                    $isOrCase = $value[1] === 'or';
                    unset($wheres[$key]);
                }

                if (!$isOrCase) $model->where($wheres);
                else {
                    $model->where(function ($query) use ($wheres) {
                        foreach ($wheres as $key => $where) {
                            if (count($where) === 2) {
                                $query->orWhere($where[0], $where[1]);
                            } else {
                                $query->orWhere($where[0], $where[1], $where[2]);
                            }
                        }
                    });
                }
            }

            if (!empty($roles)) $model->role($roles);

            return $first ? $model->select($columns)->first() : $model->select($columns)->get();
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            return false;
        }
    }

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
     * @return Collection|LengthAwarePaginator|bool
     */
    public function paginate(int $paginate = 10, array $columns = ['*'], array $relations = [], array $wheres = [], string $orderBy = 'created_at', bool $latest = true, array $roles = []): Collection|LengthAwarePaginator|bool
    {
        try {
            $model = $this->model->with($relations);

            if (!empty($orderBy)) $model->orderBy($orderBy, $latest ? 'desc' : 'asc');

            if (!empty($wheres)) {
                $isOrCase = false;

                foreach ($wheres as $key => $value) {
                    if ($value[0] !== 'whereMode') continue;

                    $isOrCase = $value[1] === 'or';
                    unset($wheres[$key]);
                }

                if (!$isOrCase) $model->where($wheres);
                else {
                    $model->where(function ($query) use ($wheres) {
                        foreach ($wheres as $key => $where) {
                            if (count($where) === 2) {
                                $query->orWhere($where[0], $where[1]);
                            } else {
                                $query->orWhere($where[0], $where[1], $where[2]);
                            }
                        }
                    });
                }
            }

            if (!empty($roles)) $model->role($roles);

            return $model->select($columns)->paginate($paginate);
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            abort(500, $th->getMessage());
        }
    }

    /**
     * Get all trashed models.
     *
     * @return Collection|bool
     */
    public function allTrashed(): Collection|bool
    {
        try {
            return $this->model->onlyTrashed()->get();
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            return false;
        }
    }

    /**
     * Find model by id.
     *
     * @param  mixed  $modelId
     * @param  array  $columns
     * @param  array  $relations
     * @param  array  $appends
     * @return Model|bool
     */
    public function findById(mixed $modelId, array $columns = ['*'], array $relations = [], array $appends = []): Model|bool
    {
        try {
            return $this->model->select($columns)->with($relations)->findOrFail($modelId)->append($appends);
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            return false;
        }
    }

    /**
     * Find model by custom id.
     *
     * @param  array  $wheres
     * @param  array  $columns
     * @param  array  $relations
     * @param  array  $appends
     * @return Model|bool
     */
    public function findByCustomId(array $wheres = [], array $columns = ['*'], array $relations = [], array $appends = []): Model|bool
    {
        try {
            return $this->model->select($columns)->with($relations)->where($wheres)->first();
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            return false;
        }
    }

    /**
     * Find trashed model by id.
     *
     * @param  int  $modelId
     * @return Model|bool
     */
    public function findTrashedById(int $modelId): Model|bool
    {
        try {
            return $this->model->withTrashed()->findOrFail($modelId);
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            return false;
        }
    }

    /**
     * Find trashed model by custom id.
     *
     * @param  array  $wheres
     * @return Model|bool
     */
    public function findTrashedByCustomId(array $wheres = []): Model|bool
    {
        try {
            return $this->model->withTrashed()->where($wheres)->firstOrFail();
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            return false;
        }
    }

    /**
     * Find only trashed model by id.
     *
     * @param  int  $modelId
     * @return Model|bool
     */
    public function findOnlyTrashedById(int $modelId): Model|bool
    {
        try {
            return $this->model->onlyTrashed()->findOrFail($modelId);
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            return false;
        }
    }

    /**
     * Find only trashed model by custom id.
     *
     * @param  array  $wheres
     * @return Model|bool
     */
    public function findOnlyTrashedByCustomId(array $wheres = []): Model|bool
    {
        try {
            return $this->model->onlyTrashed()->where($wheres)->firstOrFail();
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());

            return false;
        }
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
            $model = $this->model->query()->create($payload);
            return $model->fresh();
        });

        return $transaction;
    }

    /**
     * Update existing model.
     *
     * @param  mixed  $modelId
     * @param  array  $payload
     * @return Model
     */
    public function update(mixed $modelId, array $payload): bool
    {
        $transaction = $this->wrapIntoTransaction(function () use ($modelId, $payload) {
            return $this->model->query()->findOrFail($modelId)->update($payload);
        });

        return $transaction;
    }

    /**
     * Delete model by id.
     *
     * @param  mixed  $modelId
     * @return bool
     */
    public function deleteById(mixed $modelId): bool
    {
        $transaction = $this->wrapIntoTransaction(function () use ($modelId) {
            return $this->model->query()->findOrFail($modelId)->delete();
        });

        return $transaction;
    }

    /**
     * Restore model by id.
     *
     * @param  int  $modelId
     * @return Model
     */
    public function restoreById(int $modelId): bool
    {
        try {
            return $this->findOnlyTrashedById($modelId)->restore();
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());

            return false;
        }
    }

    /**
     * Permanently delete model by id.
     *
     * @param  int  $modelId
     * @return bool
     */
    public function permanentlyDeleteById(int $modelId): bool
    {
        try {
            return $this->findTrashedById($modelId)->forceDelete();
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());

            return false;
        }
    }

    /**
     * Get all models count.
     *
     * @return int
     */
    public function count(): int
    {
        try {
            return $this->model->count();
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            return 0;
        }
    }

    /**
     * Get all searchable fields.
     *
     * @return int
     */
    public function getSearchableFields(): array
    {
        // Check if the model has searchable fields method
        if (method_exists($this->model, 'getSearchableFields')) {
            return $this->model->getSearchableFields();
        }

        return [];
    }
}
