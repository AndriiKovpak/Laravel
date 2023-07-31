<?php
/**
 * Created by PhpStorm.
 * User: bcooper
 * Date: 4/7/2017
 * Time: 2:59 PM
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LogoutUser
{
    /**
     * Checks if the user has been logged out by an Admin (through the Refresh Session Button)
     * Checks if the user has been deleted, disabled, etc.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->SessionRefresh == true) {
                Auth::user()->SessionRefresh = false;
                Auth::logout();
                return redirect()->to('/');
            }

            // Only allow active users
            if (Auth::user()->UserStatus != 1) {
                Auth::logout();
                return redirect()->to('/');
            }
        }

        return $next($request);
    }
}