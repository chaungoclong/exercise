<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
     * @return Collection
     */
    public function findAll(array $relations = []): Collection
    {
        return $this->model->with($relations)->get();
    }


    /**
     * Find all models including soft deleted models
     *
     * @param array $relations
     * @return Collection
     */
    public function findAllWithTrashed(array $relations = []): Collection
    {
        return $this->model->withTrashed()->with($relations)->get();
    }


    /**
     * Find all soft deleted models
     *
     * @param array $relations
     * @return Collection
     */
    public function findAllOnlyTrashed(array $relations = []): Collection
    {
        return $this->model->onlyTrashed()->with($relations)->get();
    }


    /**
     * Find model by id except soft deleted models
     *
     * @param array $relations
     * @return Collection
     */
    public function findById(
        int|string $modelId,
        array $relations = [],
        array $append = []
    ): ?Model {
        return $this->model
            ->with($relations)
            ->findOrFail($modelId)
            ->append($append);
    }


    /**
     * Find model by id including soft deleted models
     *
     * @param array $relations
     * @return Collection
     */
    public function findByIdWithTrashed(
        int|string $modelId,
        array $relations = []
    ): ?Model {
        return $this->model
            ->withTrashed()
            ->with($relations)
            ->findOrFail($modelId);
    }


    /**
     * Find soft deleted model by id
     *
     * @param array $relations
     * @return Collection
     */
    public function findByIdOnlyTrashed(
        int|string $modelId,
        array $relations = []
    ): ?Model {
        return $this->model
            ->onlyTrashed()
            ->with($relations)
            ->findOrFail($modelId);
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
     * Update model by id
     *
     * @param integer|string $modelId
     * @param array $attributes
     * @return boolean
     */
    public function updateById(int|string $modelId, array $attributes): bool
    {
        $model = $this->findById($modelId);

        return $model->update($attributes);
    }


    /**
     * Update model by Model (when use Route Model Binding)
     *
     * @param integer|string $modelId
     * @param array $attributes
     * @return boolean
     */
    public function updateByModel(Model $model, array $attributes): bool
    {
        return $model->update($attributes);
    }


    /**
     * Delete model by id
     *
     * @param integer|string $modelId
     * @return boolean
     */
    public function deleteById(int|string $modelId): bool
    {
        $model = $this->findById($modelId);

        return $model->delete();
    }


    /**
     * Delete model by id
     *
     * @param integer|string $modelId
     * @return boolean
     */
    public function deleteByModel(Model $model): bool
    {
        return $model->delete();
    }


    /**
     * Force delete model by id
     *
     * @param integer|string $modelId
     * @return boolean
     */
    public function forceDeleteById(int|string $modelId): bool
    {
        $model = $this->findByIdWithTrashed($modelId);

        return $model->forceDelete();
    }


    /**
     * Force delete model by model
     *
     * @param integer|string $modelId
     * @return boolean
     */
    public function forceDeleteByModel(Model $model): bool
    {
        return $model->forceDelete();
    }


    /**
     * Restore model by id
     *
     * @param integer|string $modelId
     * @return boolean
     */
    public function restoreById(int|string $modelId): bool
    {
        $model = $this->findByIdOnlyTrashed($modelId);

        return $model->restore();
    }


    /**
     * Restore model by model
     *
     * @param integer|string $modelId
     * @return boolean
     */
    public function restoreByModel(Model $model): bool
    {
        return $model->restore();
    }
}
