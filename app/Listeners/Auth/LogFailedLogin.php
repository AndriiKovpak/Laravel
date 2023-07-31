<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    /**
     * Handle the event.
     *
     * @param  Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
        if (! is_null($event->user)) {

            $event->user->increment('InvalidLoginAttempts');
        }
    }
}
