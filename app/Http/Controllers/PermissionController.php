<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Repositories\Contracts\PermissionRepository;
use App\Repositories\Contracts\RoleRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    private PermissionRepository $permissionRepository;
    private RoleRepository $roleRepository;

    private const VIEW_INDEX = 'pages.permissions.index';

    public function __construct(
        PermissionRepository $permissionRepository,
        RoleRepository $roleRepository
    ) {
        $this->permissionRepository = $permissionRepository;
        $this->roleRepository = $roleRepository;

        $this->middleware('permission:permissions_read')
            ->only(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->roleRepository->findAll();

        return view(
            self::VIEW_INDEX,
            [
                'roles' => $roles,
                'configData' => \App\Helpers\Helper::applClasses(),
                'breadcrumbs' => config('breadcrumbs.permissions.index')
            ],
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission): JsonResponse
    {
        $permission = $permission->load('roles');

        return jsend_success(['permission' => $permission]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        //
    }

    /**
     * Datatables Of Permissions
     *
     * @return void
     */
    public function datatables()
    {
        return $this->permissionRepository->datatables();
    }
}
