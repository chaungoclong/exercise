<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Http\UploadedFile;

interface UserRepository extends BaseRepository
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

    /**
     * Create New User
     *
     * @param array $payload
     * @return User
     */
    public function createUser(array $payload): User;



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
    ): User;


    /**
     * Delete User
     *
     * @param integer|string|User $key
     * @return boolean
     */
    public function deleteUser(int|string|User $key): bool;

    /**
     * Get DataTables Of User
     *
     */
    public function datatables();
}
