<?php

namespace App\Providers;

use App\Listeners\Auth\SendEmailVerify;
use App\Models\Project;
use App\Models\Role;
use App\Observers\ProjectObserver;
use App\Observers\RoleObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use function Illuminate\Events\queueable;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],
        Registered::class => [
            SendEmailVerify::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Role::observe(RoleObserver::class);

        Project::observe(ProjectObserver::class);
    }
}
