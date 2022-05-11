<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Contracts\BaseRepository;

abstract class EloquentBaseRepository implements BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Find all models except soft deleted models
     *
     * @param array $relations
     * @param array $counts
     * @return Collection
     */
    public function findAll(array $relations = [], array $counts = []): Collection
    {
        return $this->model
            ->with($relations)
            ->withCount($counts)
            ->get();
    }


    /**
     * Find all models including soft deleted models
     *
     * @param array $relations
     * @param array $counts
     * @return Collection
     */
    public function findAllWithTrashed(
        array $relations = [],
        array $counts = []
    ): Collection {
        return $this->model
            ->withTrashed()
            ->with($relations)
            ->withCount($counts)
            ->get();
    }


    /**
     * Find all soft deleted models
     *
     * @param array $relations
     * @param array $counts
     * @return Collection
     */
    public function findAllOnlyTrashed(
        array $relations = [],
        array $counts = []
    ): Collection {
        return $this->model
            ->onlyTrashed()
            ->with($relations)
            ->withCount($counts)
            ->get();
    }


    /**
     * Find model by id except soft deleted models
     *
     * @param integer|string $modelId
     * @param array $relations
     * @param array $counts
     * @param array $appends
     * @return Model|null
     */
    public function findById(
        int|string $modelId,
        array $relations = [],
        array $counts = [],
        array $appends = []
    ): ?Model {
        return $this->model
            ->with($relations)
            ->withCount($counts)
            ->findOrFail($modelId)
            ->append($appends);
    }


    /**
     * Find model by id including soft deleted models
     *
     * @param integer|string $modelId
     * @param array $relations
     * @param array $counts
     * @param array $appends
     * @return Model|null
     */
    public function findByIdWithTrashed(
        int|string $modelId,
        array $relations = [],
        array $counts = [],
        array $appends = []
    ): ?Model {
        return $this->model
            ->withTrashed()
            ->with($relations)
            ->withCount($counts)
            ->findOrFail($modelId)
            ->append($appends);
    }


    /**
     * Find soft deleted model by id
     *
     * @param integer|string $modelId
     * @param array $relations
     * @param array $counts
     * @param array $appends
     * @return Model|null
     */
    public function findByIdOnlyTrashed(
        int|string $modelId,
        array $relations = [],
        array $counts = [],
        array $appends = []
    ): ?Model {
        return $this->model
            ->onlyTrashed()
            ->with($relations)
            ->withCount($counts)
            ->findOrFail($modelId)
            ->append($appends);
    }


    /**
     * Create new model
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }


    /**
     * Update by Id or Model
     *
     * @param integer|string|Model $key
     * @param array $attributes
     * @return Model
     */
    public function update(int|string|Model $key, array $attributes): Model
    {
        $model = ($key instanceof Model) ? $key : $this->findById($key);

        $model->update($attributes);

        return $model;
    }


    /**
     * Delete by Id Or Model
     *
     * @param integer|string|Model $key
     * @return boolean
     */
    public function delete(int|string|Model $key): bool
    {
        if ($key instanceof Model) {
            return $key->delete();
        }

        $model = $this->findById($key);

        return $model->delete();
    }


    /**
     * Force delete by Id or Model
     *
     * @param integer|string|Model $key
     * @return boolean
     */
    public function forceDelete(int|string|Model $key): bool
    {
        if ($key instanceof Model) {
            return $key->forceDelete();
        }

        $model = $this->findByIdWithTrashed($key);

        return $model->forceDelete();
    }


    /**
     * Restore by Id or Model
     *
     * @param integer|string|Model $key
     * @return boolean
     */
    public function restore(int|string|Model $key): bool
    {
        if ($key instanceof Model) {
            return $key->restore();
        }

        $model = $this->findByIdOnlyTrashed($key);

        return $model->restore();
    }
}
