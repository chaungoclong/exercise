<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Contracts\ReportRepository;
use App\Repositories\Contracts\ProjectRepository;
use App\Repositories\Contracts\PositionRepository;
use App\Repositories\Contracts\PermissionRepository;
use App\Repositories\Eloquent\Role\EloquentRoleRepository;
use App\Repositories\Eloquent\User\EloquentUserRepository;
use App\Repositories\Eloquent\Report\EloquentReportRepository;
use App\Repositories\Eloquent\Project\EloquentProjectRepository;
use App\Repositories\Eloquent\Position\EloquentPositionRepository;
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
        app()->singleton(
            UserRepository::class,
            EloquentUserRepository::class
        );

        // RoleRepository
        app()->singleton(
            RoleRepository::class,
            EloquentRoleRepository::class
        );

        // PermissionRepository
        app()->singleton(
            PermissionRepository::class,
            EloquentPermissionRepository::class
        );

        // PositionRepository
        app()->singleton(
            PositionRepository::class,
            EloquentPositionRepository::class
        );

        // ProjectRepository
        app()->singleton(
            ProjectRepository::class,
            EloquentProjectRepository::class
        );

        // ReportRepository
        app()->singleton(
            ReportRepository::class,
            EloquentReportRepository::class
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
