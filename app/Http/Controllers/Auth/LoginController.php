<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;

/**
 * Class LoginController
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /**
     * Handle login
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        $authThing = [
            'UserName' => $request->UserName,
            'password' => $request->Password,

            // Only allow active users
            'UserStatus' => 1,
        ];

        if (auth()->attempt($authThing, ($request->input('Remember') == 'on'))) {

            return redirect()->intended(route('dashboard.home.index'));
        }

        return redirect()->back()
            ->withInput($request->only('UserName', 'Remember'))
            ->withErrors(['UserName' => trans('auth.failed')]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->route('index.index');
    }
}
