<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Eloquent\EloquentBaseRepository;

class EloquentUserRepository extends EloquentBaseRepository implements UserRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
