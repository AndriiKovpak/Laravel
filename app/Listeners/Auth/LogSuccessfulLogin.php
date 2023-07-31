<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->user->update(['InvalidLoginAttempts' => 0]);
    }
}
