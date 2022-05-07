<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Contracts\PermissionRepository;
use App\Repositories\Eloquent\Role\EloquentRoleRepository;
use App\Repositories\Eloquent\User\EloquentUserRepository;
use App\Repositories\Eloquent\Permission\EloquentPermissionRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // UserRepository
        app()->singleton(UserRepository::class, EloquentUserRepository::class);

        // RoleRepository
        app()->singleton(RoleRepository::class, EloquentRoleRepository::class);

        // PermissionRepository
        app()->singleton(
            PermissionRepository::class,
            EloquentPermissionRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
