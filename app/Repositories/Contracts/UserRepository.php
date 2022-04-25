<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepository
{
    /**
     * [registerUser description]
     * @param  array  $payload [description]
     * @return [User]          [description]
     */
    public function registerUser(array $payload): User;

    /**
     * Upload avatar
     * @param  [type] $fileUploaded [description]
     * @return [type]               [description]
     */
    public function uploadAvatar($fileUploaded): string;
}
