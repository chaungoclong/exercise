<?php

namespace App\Services\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 *
 */
class UserService
{
    private User $userModel;
    private Role $roleModel;
    
    public function __construct(Role $roleModel, User $userModel)
    {
        $this->roleModel = $roleModel;
        $this->userModel = $userModel;
    }

    /**
     * [createUser description]
     * @param  array  $payload [description]
     * @return [type]          [description]
     */
    public function createUser(array $payload): User
    {
        // upload avatar
        if (!empty($payload['avatar'])) {
            $file = $payload['avatar'];
            $filePath = asset('storage/' . $file->store('avatar', 'public'));
            $payload['avatar'] = $filePath;
        }

        $payload['password'] = Hash::make($payload['password']);

        try {
            return $this->userModel->create($payload);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
