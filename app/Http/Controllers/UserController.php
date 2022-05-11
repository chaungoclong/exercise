<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    private UserRepository $userRepository;
    private RoleRepository $roleRepository;
    private const VIEW_INDEX = 'pages.users.index';
    private const VIEW_SHOW_USER = 'pages.users.show';


    public function __construct(
        UserRepository $userRepository,
        RoleRepository $roleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;

        $this->middleware('permission:users_read')
            ->only('index');

        $this->middleware('permission:users_create')
            ->only(['create', 'store']);

        $this->middleware('permission:users_update')
            ->only(['edit', 'update', 'switchStatus']);

        $this->middleware('permission:roles_delete')
            ->only(['destroy']);
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
                'breadcrumbs' => config('breadcrumbs.users.index')
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roleOptions = toSelect2(
            $this->roleRepository->findAll(),
            'id',
            'name'
        );

        return jsend_success(['roleOptions' => $roleOptions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try {
            $payload = $request->all();

            // Upload Avatar If Exist
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $path = $this->userRepository->uploadAvatar($file);
                $payload['avatar'] = $path;
            }

            $this->userRepository->createUser($payload);
        } catch (\Exception $e) {
            Log::error($e);

            throw new \Exception(__('Create failed'));
        }

        return jsend_success(null, __('Create success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user = $user->load(['projects', 'role']);

        $breadcrumbs =  [
            ['link' => "home", 'name' => "Home"],
            ['link' => 'users', 'name' => "List User"],
            ['name' => $user->full_name]
        ];

        return view(
            self::VIEW_SHOW_USER,
            [
                'user' => $user,
                'breadcrumbs' => $breadcrumbs
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roleOptions = toSelect2(
            $this->roleRepository->findAll(),
            'id',
            'name'
        );

        $idRoleSelected = optional($user->role)->id;

        return jsend_success([
            'roleOptions' => $roleOptions,
            'user' => $user,
            'idRoleSelected' => $idRoleSelected
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        // Cannot Update Yourself, Only update Yourself in Profile
        Gate::denyIf(
            (auth()->id() === $user->id),
            __("Cannot Update Yourself")
        );

        // Cannot Edit admin default
        Gate::denyIf(
            $user->isAdmin() && !$user->is_user_define,
            __('This action is unauthorized.')
        );

        try {
            $payload = $request->all();

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $path = $this->userRepository->uploadAvatar($file);
                $payload['avatar'] = $path;
            }

            $this->userRepository->updateUser($user, $payload);
        } catch (\Exception $e) {
            Log::error($e);

            throw new \Exception(__('Create failed'));
        }

        return jsend_success(null, __('Update success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Cannot delete User default
        Gate::denyIf(
            !$user->is_user_define,
            __('This action is unauthorized.')
        );

        try {
            $this->userRepository->deleteUser($user);
        } catch (\Exception $e) {
            throw new \Exception(__('Delete failed'));
        }

        return jsend_success(null, __('Delete success'));
    }

    /**
     * Get DataTables Of User
     *
     */
    public function datatables()
    {
        return $this->userRepository->datatables();
    }

    /**
     * Switch User's Status
     *
     * @param User $user
     * @param Request $request
     * @return void
     */
    public function switchStatus(User $user, Request $request)
    {
        // Cannot delete Admin default
        Gate::denyIf(
            $user->isAdmin() && !$user->is_user_define,
            __('This action is unauthorized.')
        );

        try {
            $status = (bool) $request->status;

            $this->userRepository->updateUser($user, ['status' => $status]);
        } catch (\Exception $e) {
            throw new \Exception(__('Update failed'));
        }

        return jsend_success(null, __('Update success'));
    }
}
