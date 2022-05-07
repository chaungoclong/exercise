<?php

namespace App\Services\User;


use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserService
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }
}
