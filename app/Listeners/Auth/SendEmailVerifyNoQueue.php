<?php

namespace App\Listeners\Auth;

use App\Jobs\SendEmailVerify;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailVerifyNoQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        SendEmailVerify::dispatch($event->user)
            ->onConnection('database')
            ->onQueue('test')
            ->afterCommit();
    }
}
