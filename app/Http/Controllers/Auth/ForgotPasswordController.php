<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Password\SendEmailRequest;

use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email', [
            'status'    =>  session('auth.password.status')
        ]);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \App\Http\Requests\Auth\Password\SendEmailRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(SendEmailRequest $request)
    {
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('EmailAddress')
        );

        return ($response == Password::RESET_LINK_SENT)
            ? back()->with('auth.password.status', trans($response))
            : back()->withErrors(['EmailAddress' => trans($response)]);
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
}
