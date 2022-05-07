<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Role\RoleService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Exceptions\NoPermissionException;
use App\Http\Requests\Role\CreateRequest;
use App\Http\Requests\Role\UpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class RoleController extends Controller
{
    private RoleService $roleService;
    private UserService $userService;
    private Role $roleModel;
    private Permission $permissionModel;

    private const VIEW_LIST_ROLE = 'components.blocks.list-role';
    private const VIEW_INDEX = 'pages.roles.index';

    public function __construct(
        RoleService $roleService,
        UserService $userService,
        Role $roleModel,
        Permission $permissionModel
    ) {
        $this->roleService = $roleService;
        $this->userService = $userService;
        $this->roleModel = $roleModel;
        $this->permissionModel = $permissionModel;

        $this->middleware('permission:roles_read')
            ->only('index');

        $this->middleware('permission:roles_create')
            ->only(['create', 'store']);

        $this->middleware('permission:roles_update')
            ->only(['edit', 'update', 'restore']);

        $this->middleware('permission:roles_delete')
            ->only(['destroy', 'forceDelete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = $this->roleModel->getListIndex();

        return view(
            self::VIEW_INDEX,
            [
                'roles' => $roles,
                'configData' => \App\Helpers\Helper::applClasses(),
                'breadcrumbs' => config('breadcrumbs.roles.index')
            ],
        );
    }


    /**
     * Get Data For Form Create
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        $permissionOptions = $this->permissionModel
            ->getAllSelectOptions();

        return jsend_success(['permissionOptions' => $permissionOptions]);
    }

    /**
     * Create New Role
     *
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function store(CreateRequest $request): JsonResponse
    {
        try {
            $this->roleService
                ->createRole($request->all());
        } catch (\Exception $e) {
            Log::error($e);

            throw new \Exception(__('Create Role failed'));
        }

        return jsend_success(null, __('Create Role success'));
    }


    /**
     * Get Data For Edit
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function edit(Role $role): JsonResponse
    {
        $permissionOptions = $this->permissionModel
            ->getAllSelectOptions();

        $idPermissionsSelected = $role->permissions->pluck('id')->toArray();

        return jsend_success(
            [
                'role' => $role,
                'permissionOptions' => $permissionOptions,
                'idPermissionsSelected' => $idPermissionsSelected
            ]
        );
    }

    /**
     * Update Role
     *
     * @param UpdateRequest $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Role $role): JsonResponse
    {
        try {
            $this->roleService->updateRole($role, $request->all());
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error($e);

            throw new \Exception(__('Update Role failed'));
        }

        return jsend_success(null, __('Update Role success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        try {
            $this->roleService->deleteRole($role);
        } catch (NoPermissionException | ModelNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception(__('Delete failed'));
        }

        return jsend_success(null, __('Delete success'));
    }

    /**
     * Force Delete Role
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function forceDelete(Request $request, string $id): JsonResponse
    {
        try {
            $this->roleService->forceDeleteRole($id);
        } catch (NoPermissionException | ModelNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception(__('Delete failed'));
        }

        return jsend_success(null, __('Delete success'));
    }


    /**
     * Restore Role
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function restore(Request $request, string $id): JsonResponse
    {
        try {
            $this->roleService->restoreRole($id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception(__('Restore failed'));
        }

        return jsend_success(null, __('Restore success'));
    }


    /**
     * Get Datatables Of Role
     *
     * @return void
     */
    public function datatables()
    {
        return $this->roleService->datatables();
    }
}
