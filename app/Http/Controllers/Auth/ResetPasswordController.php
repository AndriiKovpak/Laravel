<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Auth\Password\ResetPasswordRequest;

use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm($token = null)
    {
        return view('auth.passwords.reset', [
            'token'     =>  $token,
            'status'    =>  session()->get('status')
        ]);
    }

    /**
     * Reset the given user's password.
     *
     * @param  ResetPasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(ResetPasswordRequest $request)
    {
        // dd($request->all());
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        // $creds = $request->only(['Token', 'Password', 'PasswordConfirmation']);
        $creds = [
            'EmailAddress' => $this->getEmailForToken($request['Token']),
            'password' => $request['Password'],
            'password_confirmation' => $request['PasswordConfirmation'],
            'token' => $request['Token'],
        ];

        $response = $this->broker()->reset($creds,
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? redirect()->route('dashboard.home.index')->with('status', trans($response))
            : redirect()->back()->with('status', trans($response));
    }

    /**
     * Reset the given user's password.
     *
     * @param  User  $user
     * @param  string  $password
     * @return void
     */
    public function resetPassword(User $user, $password)
    {
        $user->forceFill([
            'password'  =>  $password, // The model itself will hash this value
            $user->getRememberTokenName() => Str::random(60),
        ])->save();

        auth()->guard()->login($user);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    public function getEmailForToken($token)
    {
        $passwordResets = DB::table('password_resets')->get();

        foreach ($passwordResets as $passwordReset) {
            if (Hash::check($token, $passwordReset->token)) {
                return $passwordReset->email;
            }
        }

        return null;
    }
}
