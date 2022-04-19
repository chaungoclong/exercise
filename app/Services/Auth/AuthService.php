<?php

namespace App\Services\Auth;

use App\Models\Role;
use App\Models\User;
use App\Services\User\UserService;

/**
 *
 */
class AuthService
{
    private UserService $userService;
    private Role $roleModel;

    public function __construct(UserService $userService, Role $roleModel)
    {
        $this->userService = $userService;
        $this->roleModel = $roleModel;
    }

    public function registerUser(array $payload): User
    {
        $roleDefault = $this->roleModel->getDefault();

        if ($roleDefault !== null && $roleDefault->isAminGroup()) {
            throw new Exception('Cannot Register');
        }

        $payload['role_id'] = optional($roleDefault)->id;

        return $this->userService->createUser($payload);
    }
}
