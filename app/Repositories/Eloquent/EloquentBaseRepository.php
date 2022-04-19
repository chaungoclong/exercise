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
     * Get all models.
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }


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
    ): ?Model {
        return $this->model->select($columns)->with($relations)
            ->findOrFail($modelId)->append($appends);
    }

    /**
     * Create a model.
     *
     * @param array $payload
     * @return Model
     */
    public function create(array $payload): ?Model
    {
        return $this->model->create($payload);
    }

    /**
     * Update existing model by id
     *
     * @param int|string $modelId
     * @param array $payload
     * @return bool
     */
    public function updateById(int|string $modelId, array $payload): bool
    {
        $model = $this->findById($modelId);

        return $model->update($payload);
    }

    /**
    * Update exist model by id or model
    * @param  int|string|Model  $model
    * @param  array  $payload
    * @return bool
    */
    public function update(int|string|Model $model, array $payload): bool
    {
        // when $model instanceof Model
        if ($model instanceof Model) {
            return $model->update($payload);
        }

        return $this->updateById($model, $payload);
    }

    /**
     * Delete model by id.
     *
     * @param int|string $modelId
     * @return bool
     */
    public function deleteById(int|string $modelId): bool
    {
        return $this->findById($modelId)->delete();
    }

    /**
     * delete exist model by id or model
     * @param  int|string|Model  $model [description]
     * @return bool
     */
    public function delete(int|string|Model $model): bool
    {
        // when $model instanceof Model
        if ($model instanceof Model) {
            return $model->delete();
        }

        return $this->deleteById($model);
    }
}
