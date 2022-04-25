<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Eloquent\EloquentBaseRepository;

class EloquentUserRepository extends EloquentBaseRepository implements UserRepository
{
    private RoleRepository $roleRepository;

    public function __construct(User $model, RoleRepository $roleRepository)
    {
        $this->model = $model;
        $this->roleRepository = $roleRepository;
    }

    /**
     * register user
     * @param  array  $payload [description]
     * @return [User]          [description]
     */
    public function registerUser(array $payload): User
    {
        $roleDefault = $this->roleRepository->getDefault();

        if ($roleDefault !== null && $roleDefault->isAdminGroup()) {
            throw new \Exception();
        }

        $payload['role_id'] = optional($roleDefault)->id;

        return $this->create($payload);
    }

    /**
     * [uploadAvatar description]
     * @param  [type] $fileUploaded [description]
     * @return [type]               [description]
     */
    public function uploadAvatar($fileUploaded): string
    {
        $pathUpload = config('uploadfile.avatar.path', '');
        $diskUpload = config('uploadfile.avatar.disk', '');
        $rootFolder = config('uploadfile.avatar.root', '');

        $filePath = asset(
            $rootFolder . '/' . $fileUploaded->store($pathUpload, $diskUpload)
        );

        return $filePath;
    }
}
