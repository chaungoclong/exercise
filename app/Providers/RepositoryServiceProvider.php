<?php

namespace App\Providers;

use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Eloquent\Role\EloquentRoleRepository;
use App\Repositories\Eloquent\User\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

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
