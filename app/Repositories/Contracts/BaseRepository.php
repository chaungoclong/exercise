<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepository
{
    /**
     * Find all models except soft deleted models
     *
     * @param array $relations
     * @return Collection
     */
    public function findAll(array $relations = []): Collection;


    /**
     * Find all models including soft deleted models
     *
     * @param array $relations
     * @return Collection
     */
    public function findAllWithTrashed(array $relations = []): Collection;


    /**
     * Find all soft deleted models
     *
     * @param array $relations
     * @return Collection
     */
    public function findAllOnlyTrashed(array $relations = []): Collection;


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
    ): ?Model;


    /**
     * Find model by id including soft deleted models
     *
     * @param array $relations
     * @return Collection
     */
    public function findByIdWithTrashed(
        int|string $modelId,
        array $relations = []
    ): ?Model;


    /**
     * Find soft deleted model by id
     *
     * @param array $relations
     * @return Collection
     */
    public function findByIdOnlyTrashed(
        int|string $modelId,
        array $relations = []
    ): ?Model;


    /**
     * Create new model
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;


    /**
     * Update model by id
     *
     * @param integer|string $modelId
     * @param array $attributes
     * @return boolean
     */
    public function updateById(int|string $modelId, array $attributes): bool;


    /**
     * Update model by Model (when use Route Model Binding)
     *
     * @param integer|string $modelId
     * @param array $attributes
     * @return boolean
     */
    public function updateByModel(Model $model, array $attributes): bool;


    /**
     * Delete model by id
     *
     * @param integer|string $modelId
     * @return boolean
     */
    public function deleteById(int|string $modelId): bool;


    /**
     * Delete model by id
     *
     * @param integer|string $modelId
     * @return boolean
     */
    public function deleteByModel(Model $model): bool;


    /**
     * Force delete model by id
     *
     * @param integer|string $modelId
     * @return boolean
     */
    public function forceDeleteById(int|string $modelId): bool;


    /**
     * Force delete model by model
     *
     * @param integer|string $modelId
     * @return boolean
     */
    public function forceDeleteByModel(Model $model): bool;


    /**
     * Restore model by id
     *
     * @param integer|string $modelId
     * @return boolean
     */
    public function restoreById(int|string $modelId): bool;


    /**
     * Restore model by model
     *
     * @param integer|string $modelId
     * @return boolean
     */
    public function restoreByModel(Model $model): bool;
}
