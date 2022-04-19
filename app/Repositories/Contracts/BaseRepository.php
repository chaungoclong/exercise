<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepository
{
    /**
     * Get all models.
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;


    /**
     * Find model by id.
     *
     * @param int|string $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model
     */
    public function findById(
        int|string $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model;

    /**
     * Create a model.
     *
     * @param array $payload
     * @return Model
     */
    public function create(array $payload): ?Model;

    /**
     * Update existing model by id
     *
     * @param int|string $modelId
     * @param array $payload
     * @return bool
     */
    public function updateById(int|string $modelId, array $payload): bool;

   /**
    * Update exist model by id or model
    * @param  int|string|Model  $model
    * @param  array  $payload
    * @return bool
    */
    public function update(int|string|Model $model, array $payload): bool;

    /**
     * Delete model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function deleteById(int|string $modelId): bool;

    /**
     * delete exist model by id or model
     * @param  int|string|Model  $model [description]
     * @return bool
     */
    public function delete(int|string|Model $model): bool;
}
