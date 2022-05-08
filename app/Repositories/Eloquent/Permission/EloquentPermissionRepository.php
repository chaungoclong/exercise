<?php

namespace App\Repositories\Eloquent\Permission;

use App\Models\Permission;
use App\Repositories\Contracts\PermissionRepository;
use App\Repositories\Eloquent\EloquentBaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Yajra\DataTables\Facades\DataTables;

class EloquentPermissionRepository extends EloquentBaseRepository implements
    PermissionRepository
{
    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    /**
     * Convert Permission|Collection of Permission to array for Select2 Plugin
     *
     * @param Permission|Collection|null $data
     * @return array
     */
    public function convertToSelectOption(Permission|Collection|null $data): array
    {
        return $this->model->convertToSelectOptions($data);
    }


    /**
     * Get all select options
     *
     * @return array
     */
    public function getAllSelectOptions(): array
    {
        return $this->convertToSelectOption($this->findAll());
    }


    /**
     * Get DataTables Of Permissions
     *
     */
    public function datatables()
    {
        $model = $this->model
            ->with('roles')
            ->withCount('roles');


        return DataTables::of($model)
            ->filter(function ($query) {
                // Filter By Role
                if (request()->has('role_id') && !is_null(request('role_id'))) {
                    $roleId = request('role_id');

                    $query->whereHas(
                        'roles',
                        function ($subQuery) use ($roleId) {
                            $subQuery->where('id', $roleId);
                        }
                    );
                }

                // Search By Name And Slug
                if (request()->has('search')) {
                    $search = request('search');

                    $query->where(function ($subQuery) use ($search) {
                        $subQuery->where('name', 'LIKE', "%$search%")
                            ->orWhere('slug', 'LIKE', "%$search%");
                    });
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
            ->addColumn('html', function ($permission) {
                return view(
                    'components.datatables.permission-card',
                    ['permission' => $permission]
                )->render();
            })
            ->rawColumns(['html'])
            ->make(true);
    }
}
