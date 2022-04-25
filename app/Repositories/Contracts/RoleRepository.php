<?php

namespace App\Repositories\Contracts;

use App\Models\Role;

interface RoleRepository
{
    /**
     * get role default
     * @return [NULL|Role] [description]
     */
    public function getDefault(): ?Role;
}
