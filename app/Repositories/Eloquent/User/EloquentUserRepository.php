<?php

namespace App\Repositories\Eloquent\User;

use App\Exceptions\CustomException;
use App\Models\User;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Eloquent\EloquentBaseRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class EloquentUserRepository extends EloquentBaseRepository implements
    UserRepository
{
    private RoleRepository $roleRepository;

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
}
