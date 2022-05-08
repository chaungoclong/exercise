<?php

namespace App\Services\Permission;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class PermissionService
{
    private Permission $permissionModel;

    public function __construct(Permission $permissionModel)
    {
        $this->permissionModel = $permissionModel;
    }


    /**
     * Get DataTables Of Permissions
     *
     */
    public function datatables()
    {
        $model = $this->permissionModel
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
