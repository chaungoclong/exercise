<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Http\UploadedFile;

interface UserRepository
{
    /**
     * Register user
     *
     * @param array $attributes
     * @return User
     */
    public function registerUser(array $attributes): User;

    /**
     * Upload avatar
     *
     * @param UploadedFile $fileUploaded
     * @return string
     */
    public function uploadAvatar(UploadedFile $fileUploaded): string;


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
    ): ?bool;
}
