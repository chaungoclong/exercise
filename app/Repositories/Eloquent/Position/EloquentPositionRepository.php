<?php

namespace App\Repositories\Eloquent\Position;

use App\Models\Position;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Contracts\PositionRepository;
use App\Repositories\Eloquent\EloquentBaseRepository;

class EloquentPositionRepository extends EloquentBaseRepository implements
    PositionRepository
{
    private const VIEW_CARD_POSITION = 'components.datatables.position-card';

    public function __construct(Position $model)
    {
        $this->model = $model;
    }

    /**
     * Create New Position
     *
     * @param array $payload
     * @return Position
     */
    public function createPosition(array $payload): Position
    {
        try {
            DB::beginTransaction();

            // Create New Position
            $position = $this->create($payload);

            DB::commit();

            return $position;
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }


    /**
     * Update Position, Do Not Update 'slug' Attribute
     *
     * @param string|integer|Position $key
     * @param array $payload
     * @return Position
     */
    public function updatePosition(
        string|int|Position $key,
        array $payload
    ): Position {
        $position = null;

        // Find Position For Edit
        if ($key instanceof Position) {
            $position = $key;
        } else {
            $position = $this->findById($key);
        }

        // Update Position
        try {
            DB::beginTransaction();

            $position->update(Arr::only($payload, 'name'));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return $position;
    }


    /**
     * Delete Position
     *
     * @param integer|string|v $key
     * @return boolean
     */
    public function deletePosition(int|string|Position $key): bool
    {
        $position = null;

        // Find Position For Delete
        if ($key instanceof Position) {
            $position = $key;
        } else {
            $position = $this->findById($key);
        }

        try {
            DB::beginTransaction();

            $result = $position->delete();

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }


    /**
     * Force Delete Position
     *
     * @param integer|string|Position $key
     * @return bool
     */
    public function forceDeletePosition(int|string|Position $key): bool
    {
        $position = null;

        // Find Position For Force Delete
        if ($key instanceof Position) {
            $position = $key;
        } else {
            $position = $this->findByIdWithTrashed($key);
        }

        try {
            DB::beginTransaction();

            $result = $position->forceDelete();

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }


    /**
     * Restore Position
     *
     * @param integer|string|Position $key
     * @return bool
     */
    public function restorePosition(int|string|Position $key): bool
    {
        $position = null;

        // Find Role For Restore
        if ($key instanceof Position) {
            $position = $key;
        } else {
            $position = $this->findByIdOnlyTrashed($key);
        }

        try {
            DB::beginTransaction();

            $result = $position->restore();

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Get Datatables Of Position
     *
     */
    public function datatables()
    {
        $eloquent = $this->model->query();

        return DataTables::of($eloquent)
            ->filter(function ($query) {
                // Search By Name And Slug
                if (request()->has('search')) {
                    $search = request('search');

                    $query->where('name', 'LIKE', "%$search%")
                        ->orWhere('slug', 'LIKE', "%$search%");
                }

                // Sort By Time and Alphabet
                if (request()->has('sort')) {
                    switch (request('sort')) {
                        case config('constants.sort.latest'):
                            $query->orderBy('created_at', 'desc');

                        case config('constants.sort.a-z'):
                            $query->orderBy('name', 'asc');

                        case config('constants.sort.z-a'):
                            $query->orderBy('name', 'desc');

                        case config('constants.sort.oldest'):
                        default:
                            $query->orderBy('created_at', 'asc');
                            break;
                    }
                }
            })
            ->addColumn('html', function ($position) {
                return view(
                    self::VIEW_CARD_POSITION,
                    ['position' => $position]
                )->render();
            })
            ->rawColumns(['html'])
            ->make(true);
    }
}
