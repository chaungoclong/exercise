<?php

namespace App\Repositories\Eloquent\User;

use App\Exceptions\CustomException;
use App\Exceptions\NoPermissionException;
use App\Models\User;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Eloquent\EloquentBaseRepository;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class EloquentUserRepository extends EloquentBaseRepository implements
    UserRepository
{
    private RoleRepository $roleRepository;
    private const VIEW_USER_CARD = 'components.datatables.user-card';

    public function __construct(User $model, RoleRepository $roleRepository)
    {
        $this->model = $model;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Register user
     *
     * @param array $attributes
     * @return User
     */
    public function registerUser(array $attributes): User
    {
        $roleDefault = $this->roleRepository->getDefault();

        if ($roleDefault !== null && $roleDefault->isAdminGroup()) {
            throw new \Exception();
        }

        $attributes['role_id'] = optional($roleDefault)->id;

        return $this->create($attributes);
    }

    /**
     * Upload avatar
     *
     * @param UploadedFile $fileUploaded
     * @return string
     */
    public function uploadAvatar(UploadedFile $fileUploaded): string
    {
        $pathUpload = config('uploadfile.avatar.path', '');
        $diskUpload = config('uploadfile.avatar.disk', '');
        $rootFolder = config('uploadfile.avatar.root', '');

        $filePath = asset(
            $rootFolder . '/' . ($fileUploaded->store($pathUpload, $diskUpload))
        );

        return $filePath;
    }


    /**
     * Change password
     *
     * @param User $user
     * @param string $currentPassword
     * @param string $newPassword
     * @return boolean
     */
    public function changePassword(
        User $user,
        string $currentPassword,
        string $newPassword
    ): ?bool {
        if (!Hash::check($currentPassword, $user->password)) {
            return null;
        }

        return $user->update(['password' => Hash::make($newPassword)]);
    }

    /**
     * Create New User
     *
     * @param array $payload
     * @return User
     */
    public function createUser(array $payload): User
    {
        DB::beginTransaction();

        try {
            $payload['password'] = Hash::make($payload['password']);
            $payload['status'] = (bool) ($payload['status'] ?? false);

            $user = $this->create($payload);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Update User
     *
     * @param string|integer|User $key
     * @param array $payload
     * @return bool
     */
    public function updateUser(
        string|int|User $key,
        array $payload
    ): User {
        DB::beginTransaction();

        try {
            $payload['status'] = (bool) ($payload['status'] ?? false);

            $user = $this->update($key, $payload);

            DB::commit();

            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Delete User
     *
     * @param integer|string|User $key
     * @return boolean
     */
    public function deleteUser(int|string|User $key): bool
    {
        DB::beginTransaction();

        try {
            $result = $this->delete($key);

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Get DataTables Of User
     */
    public function datatables()
    {
        $eloquent = $this
            ->model
            ->with([
                'role',
                'projectMembers.project'
            ]);

        return DataTables::of($eloquent)
            ->filter(function ($query) {
                // Filter By Role
                if (request()->has('role_id') && !is_null(request('role_id'))) {
                    $roleId = request('role_id');

                    $query->where('role_id', $roleId);
                }

                // Filter By Status
                if (request()->has('status') && !is_null(request('status'))) {
                    $status = request('status');

                    $query->where('status', $status);
                }

                // Search By Name And Slug
                if (request()->has('search')) {
                    $search = request('search');

                    $query->where(function ($subQuery) use ($search) {
                        $subQuery
                            ->where('first_name', 'LIKE', "%$search%")
                            ->orWhere('last_name', 'LIKE', "%$search%")
                            ->orWhere('username', 'LIKE', "%$search%")
                            ->orWhere('email', 'LIKE', "%$search%")
                            ->orWhere('phone', 'LIKE', "%$search%")
                            ->orWhere('address', 'LIKE', "%$search%");
                    });
                }

                // Sort By Time and Alphabet
                if (request()->has('sort')) {
                    switch (request('sort')) {
                        case config('constants.sort.latest'):
                            $query->orderBy('created_at', 'desc');

                        case config('constants.sort.a-z'):
                            $query
                                ->orderBy('first_name', 'asc')
                                ->orderBy('last_name', 'asc');

                        case config('constants.sort.z-a'):
                            $query
                                ->orderBy('first_name', 'desc')
                                ->orderBy('last_name', 'desc');

                        case config('constants.sort.oldest'):
                        default:
                            $query->orderBy('created_at', 'asc');
                            break;
                    }
                }
            })
            ->addColumn('html', function ($user) {
                return view(
                    self::VIEW_USER_CARD,
                    ['user' => $user]
                )->render();
            })
            ->rawColumns(['html'])
            ->make(true);
    }
}
