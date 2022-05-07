<?php

namespace App\Services\Role;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Exceptions\NoPermissionException;
use Yajra\DataTables\Facades\DataTables;

class RoleService
{
    private Role $roleModel;
    private const VIEW_CARD_ROLE = 'components.cards.role-card';

    public function __construct(Role $roleModel, Permission $permissionModel)
    {
        $this->roleModel = $roleModel;
        $this->permissionModel = $permissionModel;
    }


    /**
     * Create New Role
     *
     * @param array $payload
     * @return Role
     */
    public function createRole(array $payload): Role
    {
        try {
            DB::beginTransaction();

            // Create New Role
            $role = $this->roleModel
                ->create(Arr::except($payload, 'permissions'));

            // Attach Permissions
            $role->permissions()
                ->sync($payload['permissions'] ?? [], false);

            DB::commit();

            return $role;
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }


    /**
     * Update Role, Do Not Update 'slug' Attribute
     *
     * @param string|integer|Role $key
     * @param array $payload
     * @return Role
     */
    public function updateRole(
        string|int|Role $key,
        array $payload
    ): Role {
        $role = null;

        // Find Role For Edit
        if ($key instanceof Role) {
            $role = $key;
        } else {
            $role = $this->roleModel->findOrFail($key);
        }

        // Update Role
        try {
            DB::beginTransaction();

            $role->update(Arr::only($payload, 'name'));

            $role->permissions()
                ->sync($payload['permissions'] ?? []);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return $role;
    }


    /**
     * Delete Role
     *
     * @param integer|string|Role $key
     * @return boolean
     */
    public function deleteRole(int|string|Role $key): bool
    {
        $role = null;

        // Find Role For Delete
        if ($key instanceof Role) {
            $role = $key;
        } else {
            $role = $this->roleModel->findOrFail($key);
        }

        // Can Delete?
        if (!$role->is_user_define) {
            throw new NoPermissionException(__('This action is unauthorized.'));
        }

        try {
            DB::beginTransaction();

            $result = $role->delete();

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }


    /**
     * Force Delete Role
     *
     * @param integer|string|Role $key
     * @return void
     */
    public function forceDeleteRole(int|string|Role $key)
    {
        $role = null;

        // Find Role For Force Delete
        if ($key instanceof Role) {
            $role = $key;
        } else {
            $role = $this->roleModel->findWithTrashed($key);
        }

        // Can Delete?
        if (!$role->is_user_define) {
            throw new NoPermissionException(__('This action is unauthorized.'));
        }

        try {
            DB::beginTransaction();

            $result = $role->forceDelete();

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }


    /**
     * Restore Role
     *
     * @param integer|string|Role $key
     * @return void
     */
    public function restoreRole(int|string|Role $key)
    {
        $role = null;

        // Find Role For Restore
        if ($key instanceof Role) {
            $role = $key;
        } else {
            $role = $this->roleModel->findOnlyTrashed($key);
        }

        try {
            DB::beginTransaction();

            $result = $role->restore();

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Get Datatables Of Role
     *
     */
    public function datatables()
    {
        $model = $this->roleModel
            ->with('users')
            ->withCount('users');

        return DataTables::of($model)
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
            ->addColumn('html', function ($role) {
                return view(
                    self::VIEW_CARD_ROLE,
                    ['role' => $role]
                )->render();
            })
            ->rawColumns(['html'])
            ->make(true);
    }
}
