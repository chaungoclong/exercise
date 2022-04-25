<?php

namespace App\Repositories\Eloquent\Role;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Eloquent\EloquentBaseRepository;

/**
 *
 */
class EloquentRoleRepository extends EloquentBaseRepository implements RoleRepository
{
    
    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    /**
     * [getDefault description]
     * @return [NULL|Role] [description]
     */
    public function getDefault(): ?Role
    {
        return $this->model->getDefault();
    }
}
