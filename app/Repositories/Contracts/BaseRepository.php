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
     * @param array $counts
     * @return Collection
     */
    public function findAll(
        array $relations = [],
        array $counts = []
    ): Collection;


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
    ): Collection;


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
    ): Collection;


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
    ): ?Model;


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
    ): ?Model;


    /**
     *  Find soft deleted model by id
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
    ): ?Model;


    /**
     * Create new model
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;


    /**
     * Update by Id or Model
     *
     * @param integer|string|Model $key
     * @param array $attributes
     * @return boolean
     */
    public function update(int|string|Model $key, array $attributes): bool;


    /**
     * Delete by Id or Model
     *
     * @param integer|string|Model $key
     * @return boolean
     */
    public function delete(int|string|Model $key): bool;


    /**
     * Force delete by Id or Model
     *
     * @param integer|string|Model $key
     * @return boolean
     */
    public function forceDelete(int|string|Model $key): bool;


    /**
     * Restore by Id or Model
     *
     * @param integer|string|Model $key
     * @return boolean
     */
    public function restore(int|string|Model $key): bool;
}
