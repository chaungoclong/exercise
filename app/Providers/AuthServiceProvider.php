<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->hasRole(Role::ADMIN) ? true : null;
        });

        if (!$this->app->runningInConsole()) {
            Permission::all()->map(function ($permission) {
                Gate::define(
                    $permission->slug,
                    function ($user) use ($permission) {
                        return $user->hasPermission($permission);
                    }
                );
            });
        }
    }
}
